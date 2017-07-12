<?php
declare(strict_types=1);

namespace Enm\JsonApi;

use Enm\JsonApi\Model\Factory\DocumentFactory;
use Enm\JsonApi\Model\Factory\DocumentFactoryInterface;
use Enm\JsonApi\Model\Factory\RelationshipFactory;
use Enm\JsonApi\Model\Factory\RelationshipFactoryInterface;
use Enm\JsonApi\Model\Factory\ResourceFactory;
use Enm\JsonApi\Model\Factory\ResourceFactoryInterface;
use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Resource\Relationship\RelationshipInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;
use Enm\JsonApi\Serializer\Deserializer;
use Enm\JsonApi\Serializer\DocumentDeserializerInterface;
use Enm\JsonApi\Serializer\DocumentSerializerInterface;
use Enm\JsonApi\Serializer\Serializer;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
trait JsonApiTrait
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
     * @var RelationshipFactoryInterface
     */
    private $relationshipFactory;

    /**
     * @var DocumentSerializerInterface
     */
    private $documentSerializer;

    /**
     * @var DocumentDeserializerInterface
     */
    private $documentDeserializer;

    /**
     * @param string $type
     * @param string $id
     * @return ResourceInterface
     */
    public function resource(string $type, string $id): ResourceInterface
    {
        return $this->resourceFactory()->create($type, $id);
    }

    /**
     * @return string A valid uuid
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
        return $this->documentFactory()->create($resource);
    }

    /**
     * @param array $resource
     * @return DocumentInterface
     */
    public function multiResourceDocument(array $resource = []): DocumentInterface
    {
        return $this->documentFactory()->create($resource);
    }

    /**
     * @param DocumentInterface $document
     * @return array
     */
    public function serializeDocument(DocumentInterface $document): array
    {
        return $this->documentSerializer()->serializeDocument($document);
    }

    /**
     * @param array $document
     * @return DocumentInterface
     */
    public function deserializeDocument(array $document): DocumentInterface
    {
        return $this->documentDeserializer()->deserializeDocument($document);
    }

    /**
     * @param string $name
     * @param ResourceInterface|null $related
     * @return RelationshipInterface
     */
    public function toOneRelationship(string $name, ResourceInterface $related = null): RelationshipInterface
    {
        return $this->relationshipFactory()->create($name, $related);
    }

    /**
     * @param string $name
     * @param array|ResourceInterface[] $related
     * @return RelationshipInterface
     */
    public function toManyRelationship(string $name, array $related = []): RelationshipInterface
    {
        return $this->relationshipFactory()->create($name, $related);
    }

    /**
     * @param DocumentFactoryInterface $documentFactory
     */
    public function setDocumentFactory(DocumentFactoryInterface $documentFactory)
    {
        $this->documentFactory = $documentFactory;
    }

    /**
     * @param ResourceFactoryInterface $resourceFactory
     */
    public function setResourceFactory(ResourceFactoryInterface $resourceFactory)
    {
        $this->resourceFactory = $resourceFactory;
    }

    /**
     * @param RelationshipFactoryInterface $relationshipFactory
     */
    public function setRelationshipFactory(RelationshipFactoryInterface $relationshipFactory)
    {
        $this->relationshipFactory = $relationshipFactory;
    }

    /**
     * @param DocumentSerializerInterface $documentSerializer
     */
    public function setDocumentSerializer(DocumentSerializerInterface $documentSerializer)
    {
        $this->documentSerializer = $documentSerializer;
    }

    /**
     * @param DocumentDeserializerInterface $documentDeserializer
     */
    public function setDocumentDeserializer(DocumentDeserializerInterface $documentDeserializer)
    {
        $this->documentDeserializer = $documentDeserializer;
    }

    /**
     * @return DocumentFactoryInterface
     */
    private function documentFactory(): DocumentFactoryInterface
    {
        if (!$this->documentFactory instanceof DocumentFactoryInterface) {
            $this->documentFactory = new DocumentFactory();
        }

        return $this->documentFactory;
    }

    /**
     * @return ResourceFactoryInterface
     */
    private function resourceFactory(): ResourceFactoryInterface
    {
        if (!$this->resourceFactory instanceof ResourceFactoryInterface) {
            $this->resourceFactory = new ResourceFactory();
        }

        return $this->resourceFactory;
    }

    /**
     * @return RelationshipFactoryInterface
     */
    private function relationshipFactory(): RelationshipFactoryInterface
    {
        if (!$this->relationshipFactory instanceof RelationshipFactoryInterface) {
            $this->relationshipFactory = new RelationshipFactory();
        }

        return $this->relationshipFactory;
    }

    /**
     * @return DocumentSerializerInterface
     */
    private function documentSerializer(): DocumentSerializerInterface
    {
        if (!$this->documentSerializer instanceof DocumentSerializerInterface) {
            $this->documentSerializer = new Serializer();
        }

        return $this->documentSerializer;
    }

    /**
     * @return DocumentDeserializerInterface
     */
    private function documentDeserializer(): DocumentDeserializerInterface
    {
        if (!$this->documentDeserializer instanceof DocumentDeserializerInterface) {
            $this->documentDeserializer = new Deserializer();
        }

        return $this->documentDeserializer;
    }
}
