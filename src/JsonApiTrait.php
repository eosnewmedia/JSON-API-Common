<?php
declare(strict_types=1);

namespace Enm\JsonApi;

use Enm\JsonApi\Model\Document\Document;
use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Resource\JsonResource;
use Enm\JsonApi\Model\Resource\Relationship\Relationship;
use Enm\JsonApi\Model\Resource\Relationship\RelationshipInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
trait JsonApiTrait
{
    /**
     * @param string $type
     * @param string|null $id
     * @return ResourceInterface
     */
    protected function resource(string $type, ?string $id = null): ResourceInterface
    {
        return new JsonResource($type, $id);
    }

    /**
     * @param ResourceInterface|null $resource
     * @return DocumentInterface
     */
    protected function singleResourceDocument(ResourceInterface $resource = null): DocumentInterface
    {
        return new Document($resource);
    }

    /**
     * @param ResourceInterface[] $resource
     * @return DocumentInterface
     */
    protected function multiResourceDocument(array $resource = []): DocumentInterface
    {
        return new Document($resource);
    }

    /**
     * @param string $name
     * @param ResourceInterface|null $related
     * @return RelationshipInterface
     */
    protected function toOneRelationship(string $name, ResourceInterface $related = null): RelationshipInterface
    {
        return new Relationship($name, $related);
    }

    /**
     * @param string $name
     * @param array|ResourceInterface[] $related
     * @return RelationshipInterface
     */
    protected function toManyRelationship(string $name, array $related = []): RelationshipInterface
    {
        return new Relationship($name, $related);
    }
}
