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
     * @param string $id
     * @return ResourceInterface
     */
    public function resource(string $type, string $id): ResourceInterface
    {
        return new JsonResource($type, $id);
    }

    /**
     * @return string A valid uuid
     * @throws \Exception
     */
    public function generateUuid(): string
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

            // 32 bits for "time_low"
            random_int(0, 0xffff), random_int(0, 0xffff),

            // 16 bits for "time_mid"
            random_int(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            random_int(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            random_int(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            random_int(0, 0xffff), random_int(0, 0xffff), random_int(0, 0xffff)
        );
    }

    /**
     * @param ResourceInterface|null $resource
     * @return DocumentInterface
     */
    public function singleResourceDocument(ResourceInterface $resource = null): DocumentInterface
    {
        return new Document($resource);
    }

    /**
     * @param ResourceInterface[] $resource
     * @return DocumentInterface
     */
    public function multiResourceDocument(array $resource = []): DocumentInterface
    {
        return new Document($resource);
    }

    /**
     * @param string $name
     * @param ResourceInterface|null $related
     * @return RelationshipInterface
     */
    public function toOneRelationship(string $name, ResourceInterface $related = null): RelationshipInterface
    {
        return new Relationship($name, $related);
    }

    /**
     * @param string $name
     * @param array|ResourceInterface[] $related
     * @return RelationshipInterface
     */
    public function toManyRelationship(string $name, array $related = []): RelationshipInterface
    {
        return new Relationship($name, $related);
    }
}
