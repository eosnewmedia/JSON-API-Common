<?php
declare(strict_types=1);

namespace Enm\JsonApi\Serializer;

use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Error\ErrorInterface;
use Enm\JsonApi\Model\Resource\Extension\RelatedMetaInformationInterface;
use Enm\JsonApi\Model\Resource\Link\LinkInterface;
use Enm\JsonApi\Model\Resource\Relationship\RelationshipInterface;
use Enm\JsonApi\Model\Resource\ResourceCollectionInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class Serializer implements DocumentSerializerInterface
{
    /**
     * @var bool
     */
    private $keepEmptyData;

    /**
     * @param bool $keepEmptyData
     */
    public function __construct(bool $keepEmptyData = false)
    {
        $this->keepEmptyData = $keepEmptyData;
    }

    /**
     * @return bool
     */
    protected function shouldKeepEmptyData(): bool
    {
        return $this->keepEmptyData;
    }

    /**
     * @param DocumentInterface $document
     * @param bool $identifiersOnly
     *
     * @return array
     * @throws \Exception
     */
    public function serializeDocument(DocumentInterface $document, bool $identifiersOnly = false): array
    {
        $result = [];

        if ($this->shouldContainData($document)) {
            $result['data'] = (!$document->shouldBeHandledAsCollection() && $document->data()->isEmpty()) ?
                null : $this->createData($document, $identifiersOnly);
        }

        if (!$document->metaInformation()->isEmpty()) {
            $result['meta'] = $document->metaInformation()->all();
        }

        if (!$document->links()->isEmpty()) {
            foreach ($document->links()->all() as $link) {
                $result['links'][$link->name()] = $this->serializeLink($link);
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

        // informations about json api
        $result['jsonapi'] = [
            'version' => $document->jsonApi()->getVersion()
        ];
        if (!$document->jsonApi()->metaInformation()->isEmpty()) {
            $result['jsonapi']['meta'] = $document->jsonApi()->metaInformation()->all();
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
            'type' => $resource->type(),
            'id' => $resource->id(),
        ];

        if (!$resource->metaInformation()->isEmpty()) {
            $data['meta'] = $resource->metaInformation()->all();
        }

        if ($identifierOnly) {
            if ($resource instanceof RelatedMetaInformationInterface && !$resource->relatedMetaInformation()->isEmpty()) {
                foreach ($resource->relatedMetaInformation()->all() as $key => $value) {
                    $data['meta'][$key] = $value;
                }
            }

            return $data;
        }

        if (!$resource->attributes()->isEmpty()) {
            $data['attributes'] = $resource->attributes()->all();
        }

        foreach ($resource->relationships()->all() as $relationship) {
            $data['relationships'][$relationship->name()] = $this->serializeRelationship($relationship);
        }

        foreach ($resource->links()->all() as $link) {
            $data['links'][$link->name()] = $this->serializeLink($link);
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
        $data = [];

        foreach ($relationship->links()->all() as $link) {
            $data['links'][$link->name()] = $this->serializeLink($link);
        }

        if (!$relationship->metaInformation()->isEmpty()) {
            $data['meta'] = $relationship->metaInformation()->all();
        }

        if (!$relationship->related()->isEmpty()) {
            if ($relationship->shouldBeHandledAsCollection()) {
                $data['data'] = $this->createCollectionData($relationship->related());
            } else {
                $data['data'] = $this->serializeResource($relationship->related()->first());
            }
        } // only add empty data if links or meta are not defined
        elseif (count($data) === 0 || $this->shouldKeepEmptyData()) {
            if ($relationship->shouldBeHandledAsCollection()) {
                $data['data'] = [];
            } else {
                $data['data'] = null;
            }
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
        if (!$link->metaInformation()->isEmpty()) {
            return [
                'href' => $link->href(),
                'meta' => $link->metaInformation()->all(),
            ];
        }

        return $link->href();
    }

    /**
     * @param ErrorInterface $error
     *
     * @return array
     */
    protected function serializeError(ErrorInterface $error): array
    {
        $data = [
            'status' => $error->status(),
        ];

        if ($error->code() !== '') {
            $data['code'] = $error->code();
        }
        if ($error->title() !== '') {
            $data['title'] = $error->title();
        }
        if ($error->detail() !== '') {
            $data['detail'] = $error->detail();
        }

        if (!$error->metaInformation()->isEmpty()) {
            $data['meta'] = $error->metaInformation()->all();
        }

        return $data;
    }

    /**
     * @param DocumentInterface $document
     * @param bool $identifiersOnly
     *
     * @return array
     * @throws \Exception
     */
    protected function createData(DocumentInterface $document, bool $identifiersOnly): array
    {
        if ($document->shouldBeHandledAsCollection()) {
            return $this->createCollectionData($document->data(), $identifiersOnly);
        }

        return $this->serializeResource($document->data()->first(), $identifiersOnly);
    }

    /**
     * @param ResourceCollectionInterface $collection
     * @param $identifierOnly
     * @return array
     * @throws \Exception
     */
    protected function createCollectionData(ResourceCollectionInterface $collection, bool $identifierOnly = true): array
    {
        $data = [];
        foreach ($collection->all() as $resource) {
            $data[] = $this->serializeResource($resource, $identifierOnly);
        }

        return $data;
    }

    /**
     * @param DocumentInterface $document
     * @return bool
     */
    protected function shouldContainData(DocumentInterface $document): bool
    {
        if (!$document->data()->isEmpty()) {
            return true;
        }

        return ($document->errors()->isEmpty() && $document->metaInformation()->isEmpty()) || $this->shouldKeepEmptyData();
    }
}
