<?php
declare(strict_types=1);


namespace Enm\JsonApi;

use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Resource\Relationship\RelationshipInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;


/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface JsonApiInterface
{
    const CONTENT_TYPE = 'application/vnd.api+json';

    const CURRENT_VERSION = '1.0';

    /**
     * @return string A valid uuid
     */
    public function generateUuid(): string;

    /**
     * @param string $type
     * @param string $id
     * @return ResourceInterface
     */
    public function resource(string $type, string $id): ResourceInterface;

    /**
     * @param ResourceInterface|null $resource
     * @param string $version
     * @return DocumentInterface
     */
    public function singleResourceDocument(
        ResourceInterface $resource = null,
        string $version = self::CURRENT_VERSION
    ): DocumentInterface;

    /**
     * @param ResourceInterface[] $resource
     * @param string $version
     * @return DocumentInterface
     */
    public function multiResourceDocument(
        array $resource = [],
        string $version = self::CURRENT_VERSION
    ): DocumentInterface;

    /**
     * @param DocumentInterface $document
     * @return array
     */
    public function serializeDocument(DocumentInterface $document): array;

    /**
     * @param array $document
     * @return DocumentInterface
     */
    public function deserializeDocument(array $document): DocumentInterface;

    /**
     * @param string $name
     * @param ResourceInterface|null $related
     * @return RelationshipInterface
     */
    public function toOneRelationship(string $name, ResourceInterface $related = null): RelationshipInterface;

    /**
     * @param string $name
     * @param array|ResourceInterface[] $related
     * @return RelationshipInterface
     */
    public function toManyRelationship(string $name, array $related = []): RelationshipInterface;
}
