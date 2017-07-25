<?php
declare(strict_types=1);

namespace Enm\JsonApi\Serializer;

use Enm\JsonApi\JsonApiAwareInterface;
use Enm\JsonApi\JsonApiAwareTrait;
use Enm\JsonApi\JsonApiInterface;
use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Error\Error;
use Enm\JsonApi\Model\Error\ErrorInterface;
use Enm\JsonApi\Model\Resource\Link\LinkCollectionInterface;
use Enm\JsonApi\Model\Resource\ResourceCollectionInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class Deserializer implements DocumentDeserializerInterface, JsonApiAwareInterface
{
    use JsonApiAwareTrait;

    /**
     * @param array $documentData
     * @return DocumentInterface
     * @throws \InvalidArgumentException|\RuntimeException
     */
    public function deserializeDocument(array $documentData): DocumentInterface
    {
        $data = $documentData['data'] ?? null;

        $hasJsonApi = array_key_exists('jsonapi', $documentData);

        $version = $hasJsonApi && array_key_exists('version', $documentData['jsonapi']) ?
            (string)$documentData['jsonapi']['version'] : JsonApiInterface::CURRENT_VERSION;

        if (!is_array($data) || $this->isSingleResource($data)) {
            $document = $this->jsonApi()->singleResourceDocument(null, $version);
        } else {
            $document = $this->jsonApi()->multiResourceDocument([], $version);
        }

        if (is_array($data)) {
            if ($this->isSingleResource($data)) {
                $this->buildResource($document->data(), $data);
            } else {
                foreach ($data as $resource) {
                    $this->buildResource($document->data(), $resource);
                }
            }
        }

        if ($hasJsonApi && array_key_exists('meta', $documentData['jsonapi'])) {
            $document->jsonApi()->metaInformation()->merge((array)$documentData['jsonapi']);
        }

        $errors = array_key_exists('errors', $documentData) ? (array)$documentData['errors'] : [];
        foreach ($errors as $error) {
            $document->errors()->add($this->buildError($error));
        }

        if (array_key_exists('meta', $documentData)) {
            $document->metaInformation()->merge((array)$documentData['meta']);
        }

        $links = array_key_exists('links', $documentData) ? (array)$documentData['links'] : [];
        foreach ($links as $name => $link) {
            $this->buildLink($document->links(), $name, is_array($link) ? $link : ['href' => $link]);
        }

        $included = array_key_exists('included', $documentData) ? (array)$documentData['included'] : [];
        foreach ($included as $related) {
            $this->buildResource($document->included(), $related);
        }

        return $document;
    }

    /**
     * @param ResourceCollectionInterface $collection
     * @param array $resourceData
     * @return ResourceInterface
     * @throws \InvalidArgumentException|\RuntimeException
     */
    protected function buildResource(ResourceCollectionInterface $collection, array $resourceData): ResourceInterface
    {
        if (!array_key_exists('type', $resourceData)) {
            throw new \InvalidArgumentException('Invalid resource given!');
        }

        $type = (string)$resourceData['type'];
        $id = array_key_exists('id', $resourceData) ? (string)$resourceData['id'] : '';
        $resource = $this->jsonApi()->resource($type, $id);
        $collection->set($resource);

        if (array_key_exists('attributes', $resourceData)) {
            $resource->attributes()->merge((array)$resourceData['attributes']);
        }

        $relationships = array_key_exists('relationships', $resourceData) ? (array)$resourceData['relationships'] : [];
        $this->buildResourceRelationships($relationships, $resource);

        $links = array_key_exists('links', $resourceData) ? (array)$resourceData['links'] : [];
        foreach ($links as $name => $link) {
            $this->buildLink($resource->links(), $name, is_array($link) ? $link : ['href' => $link]);
        }

        if (array_key_exists('meta', $resourceData)) {
            $resource->metaInformation()->merge((array)$resourceData['meta']);
        }

        return $resource;
    }

    /**
     * @param array $data
     * @return ErrorInterface
     */
    protected function buildError(array $data): ErrorInterface
    {
        $error = new Error(
            array_key_exists('status', $data) ? (int)$data['status'] : 0,
            array_key_exists('title', $data) ? (string)$data['title'] : '',
            array_key_exists('detail', $data) ? (string)$data['detail'] : '',
            array_key_exists('code', $data) ? (string)$data['code'] : ''
        );

        if (array_key_exists('meta', $data)) {
            $error->metaInformation()->merge((array)$data['meta']);
        }

        return $error;
    }

    /**
     * @param LinkCollectionInterface $collection
     * @param string $name
     * @param array $data
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function buildLink(LinkCollectionInterface $collection, string $name, array $data)
    {
        if (!array_key_exists('href', $data)) {
            throw new \InvalidArgumentException('Invalid link given!');
        }

        $collection->createLink($name, (string)$data['href']);
        if (array_key_exists('meta', $data)) {
            $collection->get($name)->metaInformation()->merge((array)$data['meta']);
        }
    }

    /**
     * @param array $relationships
     * @param ResourceInterface $resource
     * @return void
     * @throws \InvalidArgumentException|\RuntimeException
     */
    protected function buildResourceRelationships(array $relationships, ResourceInterface $resource)
    {
        foreach ($relationships as $name => $relationship) {
            $related = $relationship['data'] ?? null;

            if (!is_array($related)) {
                // empty to one relationship
                $relationshipObject = $this->jsonApi()->toOneRelationship($name);
            } elseif (count($related) > 0 && array_keys($related) !== range(0, count($related) - 1)) {
                // to one relationship
                $relationshipObject = $this->jsonApi()->toOneRelationship($name);
                $this->buildResource($relationshipObject->related(), $related);
            } else {
                // to many relationship
                $relationshipObject = $this->jsonApi()->toManyRelationship($name);
                foreach ($related as $relatedResource) {
                    $this->buildResource($relationshipObject->related(), $relatedResource);
                }
            }

            $links = array_key_exists('links', $relationship) ? (array)$relationship['links'] : [];
            foreach ($links as $linkName => $link) {
                $this->buildLink(
                    $relationshipObject->links(),
                    $linkName,
                    is_array($link) ? $link : ['href' => $link]
                );
            }

            if (array_key_exists('meta', $relationship)) {
                $relationshipObject->metaInformation()->merge((array)$relationship['meta']);
            }

            $resource->relationships()->set($relationshipObject);
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function isSingleResource(array $data): bool
    {
        return count($data) > 0 && array_keys($data) !== range(0, count($data) - 1);
    }
}
