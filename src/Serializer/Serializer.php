<?php
declare(strict_types=1);

namespace Enm\JsonApi\Serializer;

use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Error\ErrorInterface;
use Enm\JsonApi\Model\Resource\Link\LinkInterface;
use Enm\JsonApi\Model\Resource\Relationship\RelationshipInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class Serializer implements DocumentSerializerInterface
{
    /**
     * @param DocumentInterface $document
     *
     * @return array
     * @throws \Exception
     */
    public function serializeDocument(DocumentInterface $document): array
    {
        $result = [];
        if ($document->getType() !== DocumentInterface::TYPE_ERROR) {
            if ($document->data()->isEmpty()) {
                $result['data'] = $this->createEmptyData($document);
            } else {
                $result['data'] = $this->createData($document);
            }
        }

        if (!$document->metaInformations()->isEmpty()) {
            $result['meta'] = $document->metaInformations()->all();
        }

        if (!$document->links()->isEmpty()) {
            foreach ($document->links()->all() as $link) {
                $result['links'][$link->getName()] = $this->serializeLink($link);
            }
        }

        if (!$document->included()->isEmpty()) {
            foreach ($document->included()->all() as $resource) {
                $result['included'][] = $this->serializeResource(
                    $resource,
                    false
                );
            }
        }

        if (!$document->errors()->isEmpty()) {
            foreach ($document->errors()->all() as $error) {
                $result['errors'][] = $this->serializeError($error);
            }
        }

        return $result;
    }

    /**
     * @param ResourceInterface $resource
     * @param bool $identifierOnly
     *
     * @return array
     * @throws \Exception
     */
    protected function serializeResource(ResourceInterface $resource, bool $identifierOnly = true): array
    {
        $data = [
            'type' => $resource->getType(),
            'id' => $resource->getId(),
        ];

        if ($identifierOnly) {
            return $data;
        }

        if (!$resource->attributes()->isEmpty()) {
            $data['attributes'] = $resource->attributes()->all();
        }

        if (!$resource->metaInformations()->isEmpty()) {
            $data['meta'] = $resource->metaInformations()->all();
        }

        foreach ($resource->relationships()->all() as $relationship) {
            $data['relationships'][$relationship->getName()] = $this->serializeRelationship($relationship);
        }

        foreach ($resource->links()->all() as $link) {
            $data['links'][$link->getName()] = $this->serializeLink($link);
        }

        return $data;
    }


    /**
     * @param RelationshipInterface $relationship
     *
     * @return array
     * @throws \Exception
     */
    protected function serializeRelationship(RelationshipInterface $relationship): array
    {
        $data = ['data' => null];

        foreach ($relationship->links()->all() as $link) {
            $data['links'][$link->getName()] = $this->serializeLink($link);
        }

        switch ($relationship->getType()) {
            case RelationshipInterface::TYPE_MANY:
                $data['data'] = [];
                foreach ($relationship->related()->all() as $identifier) {
                    $data['data'][] = $this->serializeResource($identifier);
                }
                break;
            case RelationshipInterface::TYPE_ONE:
                if (!$relationship->related()->isEmpty()) {
                    $relationshipData = $relationship->related()->all();
                    $resource = array_shift($relationshipData);

                    $data['data'] = $this->serializeResource($resource);
                }
                break;
            default:
                throw new \InvalidArgumentException('Invalid relationship');
        }

        if (!$relationship->metaInformations()->isEmpty()) {
            $data['meta'] = $relationship->metaInformations()->all();
        }

        return $data;
    }

    /**
     * @param LinkInterface $link
     *
     * @return array|string
     */
    protected function serializeLink(LinkInterface $link)
    {
        if (!$link->metaInformations()->isEmpty()) {
            return [
                'href' => $link->getHref(),
                'meta' => $link->metaInformations()->all(),
            ];
        }

        return $link->getHref();
    }

    /**
     * @param ErrorInterface $error
     *
     * @return array
     */
    protected function serializeError(ErrorInterface $error): array
    {
        $data = [
            'status' => $error->getStatus(),
        ];

        if ($error->getCode() !== '') {
            $data['code'] = $error->getCode();
        }
        if ($error->getTitle() !== '') {
            $data['title'] = $error->getTitle();
        }
        if ($error->getDetail() !== '') {
            $data['detail'] = $error->getDetail();
        }

        if (!$error->metaInformations()->isEmpty()) {
            $data['meta'] = $error->metaInformations()->all();
        }

        return $data;
    }

    /**
     * @param DocumentInterface $document
     *
     * @return array|null
     * @throws \Exception
     */
    protected function createEmptyData(DocumentInterface $document)
    {
        switch ($document->getType()) {
            case DocumentInterface::TYPE_RESOURCE_COLLECTION:
            case DocumentInterface::TYPE_RELATIONSHIP_COLLECTION:
                return [];
            case DocumentInterface::TYPE_RESOURCE:
            case DocumentInterface::TYPE_RELATIONSHIP:
                return null;
            default:
                throw new \InvalidArgumentException('Invalid document type');
        }
    }

    /**
     * @param DocumentInterface $document
     *
     * @return array
     * @throws \Exception
     */
    protected function createData(DocumentInterface $document): array
    {
        switch ($document->getType()) {
            case DocumentInterface::TYPE_RESOURCE_COLLECTION:
                return $this->createCollectionData($document, false);
            case DocumentInterface::TYPE_RELATIONSHIP_COLLECTION:
                return $this->createCollectionData($document);
            case DocumentInterface::TYPE_RESOURCE:
                return $this->serializeResource($document->data()->first(), false);
            case DocumentInterface::TYPE_RELATIONSHIP:
                return $this->serializeResource($document->data()->first());
            default:
                throw new \InvalidArgumentException('Invalid document type');
        }
    }

    /**
     * @param DocumentInterface $document
     * @param $identifierOnly
     * @return array
     * @throws \Exception
     */
    protected function createCollectionData(DocumentInterface $document, bool $identifierOnly = true): array
    {
        $data = [];
        foreach ($document->data()->all() as $resource) {
            $data[] = $this->serializeResource($resource, $identifierOnly);
        }

        return $data;
    }
}
