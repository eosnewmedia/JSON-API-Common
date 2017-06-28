<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Factory;

use Enm\JsonApi\Factory\DocumentFactory;
use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Document\ErrorDocument;
use Enm\JsonApi\Model\Document\RelationshipDocument;
use Enm\JsonApi\Model\Document\ResourceCollectionDocument;
use Enm\JsonApi\Model\Resource\ResourceInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class DocumentFactoryTest extends TestCase
{
    public function testCreateEmptyResourceDocument()
    {
        $factory = new DocumentFactory();
        $document = $factory->create(DocumentInterface::TYPE_RESOURCE);

        self::assertEmpty($document->data()->all());
    }

    public function testCreateResourceDocument()
    {
        $factory = new DocumentFactory();
        $document = $factory->create(DocumentInterface::TYPE_RESOURCE, $this->createMock(ResourceInterface::class));
        self::assertInstanceOf(ResourceInterface::class, $document->data()->first());
    }

    public function testCreateResourceCollectionDocumentWithOneResource()
    {
        $factory = new DocumentFactory();
        $document = $factory->create(
            DocumentInterface::TYPE_RESOURCE_COLLECTION,
            $this->createMock(ResourceInterface::class)
        );
        self::assertInstanceOf(ResourceInterface::class, $document->data()->first());
    }

    public function testCreateResourceCollectionDocument()
    {
        $factory = new DocumentFactory();
        $document = $factory->create(
            DocumentInterface::TYPE_RESOURCE_COLLECTION,
            [$this->createMock(ResourceInterface::class)]
        );
        self::assertInstanceOf(ResourceInterface::class, $document->data()->first());
    }

    public function testCreateRelationshipDocument()
    {
        $factory = new DocumentFactory();
        $document = $factory->create(DocumentInterface::TYPE_RELATIONSHIP);
        self::assertInstanceOf(RelationshipDocument::class, $document);
    }

    public function testCreateRelationshipCollectionDocument()
    {
        $factory = new DocumentFactory();
        $document = $factory->create(DocumentInterface::TYPE_RELATIONSHIP_COLLECTION);
        self::assertInstanceOf(ResourceCollectionDocument::class, $document);
    }

    public function testCreateErrorDocument()
    {
        $factory = new DocumentFactory();
        $document = $factory->create(DocumentInterface::TYPE_ERROR);
        self::assertInstanceOf(ErrorDocument::class, $document);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateInvalid()
    {
        $factory = new DocumentFactory();
        $factory->create('invalid');
    }
}
