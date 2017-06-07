<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Document;

use Enm\JsonApi\Model\Document\ErrorDocument;
use Enm\JsonApi\Model\Document\RelationshipCollectionDocument;
use Enm\JsonApi\Model\Document\RelationshipDocument;
use Enm\JsonApi\Model\Document\ResourceCollectionDocument;
use Enm\JsonApi\Model\Document\ResourceDocument;
use Enm\JsonApi\Model\Resource\ResourceInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class DocumentTest extends TestCase
{
    public function testResourceDocument()
    {
        $document = new ResourceDocument($this->createMock(ResourceInterface::class));
        self::assertEquals(1, $document->data()->count());
        self::assertEquals('resource', $document->getType());
    }

    public function testEmptyResourceDocument()
    {
        $document = new ResourceDocument();
        self::assertEquals(0, $document->data()->count());
    }

    public function testResourceCollectionDocument()
    {
        $document = new ResourceCollectionDocument([$this->createMock(ResourceInterface::class)]);
        self::assertEquals(1, $document->data()->count());
        self::assertEquals('resource_collection', $document->getType());
    }

    public function testEmptyResourceCollectionDocument()
    {
        $document = new ResourceCollectionDocument();
        self::assertEquals(0, $document->data()->count());
    }

    public function testRelationshipDocument()
    {
        $document = new RelationshipDocument($this->createMock(ResourceInterface::class));
        self::assertEquals(1, $document->data()->count());
        self::assertEquals('relationship', $document->getType());
    }

    public function testEmptyRelationshipDocument()
    {
        $document = new RelationshipDocument();
        self::assertEquals(0, $document->data()->count());
    }

    public function testRelationshipCollectionDocument()
    {
        $document = new RelationshipCollectionDocument([$this->createMock(ResourceInterface::class)]);
        self::assertEquals(1, $document->data()->count());
        self::assertEquals('relationship_collection', $document->getType());
    }

    public function testEmptyRelationshipCollectionDocument()
    {
        $document = new RelationshipCollectionDocument();
        self::assertEquals(0, $document->data()->count());
    }

    public function testErrorDocument()
    {
        $document = new ErrorDocument();
        self::assertEquals(0, $document->errors()->count());
        self::assertEquals(0, $document->data()->count());
        self::assertEquals('error', $document->getType());
    }

    public function testEmptyErrorDocument()
    {
        $document = new ErrorDocument();
        self::assertEquals(0, $document->errors()->count());
    }
}
