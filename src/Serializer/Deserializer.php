<?php
declare(strict_types=1);

namespace Enm\JsonApi\Serializer;

use Enm\JsonApi\Factory\DocumentFactoryInterface;
use Enm\JsonApi\Factory\ResourceFactoryInterface;
use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Error\Error;
use Enm\JsonApi\Model\Error\ErrorInterface;
use Enm\JsonApi\Model\Resource\Link\Link;
use Enm\JsonApi\Model\Resource\Link\LinkInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class Deserializer implements DocumentDeserializerInterface
{
    /**
     * @var DocumentFactoryInterface
     */
    private $documentFactory;

    /**
     * @var ResourceFactoryInterface
     */
    private $resourceFactory;

    /**
     * @param DocumentFactoryInterface $documentFactory
     * @param ResourceFactoryInterface $resourceFactory
     */
    public function __construct(DocumentFactoryInterface $documentFactory, ResourceFactoryInterface $resourceFactory)
    {
        $this->documentFactory = $documentFactory;
        $this->resourceFactory = $resourceFactory;
    }

    /**
     * @return DocumentFactoryInterface
     */
    protected function documentFactory(): DocumentFactoryInterface
    {
        return $this->documentFactory;
    }

    /**
     * @return ResourceFactoryInterface
     */
    protected function resourceFactory(): ResourceFactoryInterface
    {
        return $this->resourceFactory;
    }

    /**
     * @param string $type
     * @param array $documentData
     * @return DocumentInterface
     * @throws \InvalidArgumentException
     */
    public function deserialize(string $type, array $documentData): DocumentInterface
    {
        $data = $documentData['data'] ?? null;

        if (!is_array($data) || (!is_array($data) && $type === DocumentInterface::TYPE_ERROR)) {
            $document = $this->documentFactory()->create($type);
        } elseif (count($data) > 0 && array_keys($data) !== range(0, count($data) - 1)) {
            $document = $this->documentFactory()->create($type, $this->buildResource($data));
        } else {
            $document = $this->documentFactory()->create($type);
            foreach ($data as $resource) {
                $document->data()->set($this->buildResource($resource));
            }
        }

        $errors = array_key_exists('errors', $documentData) ? (array)$documentData['errors'] : [];
        foreach ($errors as $error) {
            $document->errors()->add($this->buildError($error));
        }

        if (array_key_exists('meta', $documentData)) {
            $document->metaInformations()->merge((array)$documentData['meta']);
        }

//        $jsonApi = array_key_exists('jsonapi', $documentData) ? $documentData['jsonapi'] : [];

        $links = array_key_exists('links', $documentData) ? (array)$documentData['links'] : [];
        foreach ($links as $name => $link) {
            $document->links()->set($this->buildLink($name, is_array($link) ? $link : ['href' => $link]));
        }

        $included = array_key_exists('included', $documentData) ? (array)$documentData['included'] : [];
        foreach ($included as $related) {
            $document->included()->set($this->buildResource($related));
        }

        return $document;
    }

    /**
     * @param array $resourceData
     * @return ResourceInterface
     * @throws \InvalidArgumentException
     */
    protected function buildResource(array $resourceData): ResourceInterface
    {
        if (!array_key_exists('type', $resourceData) || !array_key_exists('id', $resourceData)) {
            throw new \InvalidArgumentException('Invalid resource given!');
        }

        $resource = $this->resourceFactory()->create((string)$resourceData['type'], (string)$resourceData['id']);
        if (array_key_exists('attributes', $resourceData)) {
            $resource->attributes()->merge((array)$resourceData['attributes']);
        }

        $relationships = array_key_exists('relationships', $resourceData) ? (array)$resourceData['relationships'] : [];
        $this->buildResourceRelationships($relationships, $resource);

        $links = array_key_exists('links', $resourceData) ? (array)$resourceData['links'] : [];
        foreach ($links as $name => $link) {
            $resource->links()->set($this->buildLink($name, is_array($link) ? $link : ['href' => $link]));
        }

        if (array_key_exists('meta', $resourceData)) {
            $resource->metaInformations()->merge((array)$resourceData['meta']);
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
            $error->metaInformations()->merge((array)$data['meta']);
        }

        return $error;
    }

    /**
     * @param string $name
     * @param array $data
     * @return LinkInterface
     * @throws \InvalidArgumentException
     */
    protected function buildLink(string $name, array $data): LinkInterface
    {
        if (!array_key_exists('href', $data)) {
            throw new \InvalidArgumentException('Invalid link given!');
        }

        $link = new Link($name, (string)$data['href']);
        if (array_key_exists('meta', $data)) {
            $link->metaInformations()->merge((array)$data['meta']);
        }

        return $link;
    }

    /**
     * @param array $relationships
     * @param ResourceInterface $resource
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function buildResourceRelationships(array $relationships, ResourceInterface $resource)
    {
        foreach ($relationships as $name => $relationship) {
            $related = $relationship['data'] ?? null;

            if (!is_array($related)) {
                // empty to one relationship
                $resource->relationships()->createToOne($name);
            } elseif (count($related) > 0 && array_keys($related) !== range(0, count($related) - 1)) {
                // to one relationship
                $resource->relationships()->createToOne($name, $this->buildResource($related));
            } else {
                // to many relationship
                $relatedResources = [];
                foreach ($related as $relatedResource) {
                    $relatedResources[] = $this->buildResource($relatedResource);
                }
                $resource->relationships()->createToMany($name, $relatedResources);
            }

            $links = array_key_exists('links', $relationship) ? (array)$relationship['links'] : [];
            foreach ($links as $linkName => $link) {
                $resource->relationships()->get($name)->links()->set(
                    $this->buildLink($linkName, is_array($link) ? $link : ['href' => $link])
                );
            }

            if (array_key_exists('meta', $relationship)) {
                $resource->relationships()->get($name)->metaInformations()->merge((array)$relationship['meta']);
            }
        }
    }
}
