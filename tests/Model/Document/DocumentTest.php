<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Document;

use Enm\JsonApi\Model\Document\Document;
use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Resource\ResourceCollectionInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class DocumentTest extends TestCase
{
    public function testResourceDocument()
    {
        $document = new Document($this->createMock(ResourceInterface::class));
        self::assertEquals(1, $document->data()->count());
        self::assertFalse($document->shouldBeHandledAsCollection());
    }

    public function testEmptyResourceDocument()
    {
        $document = new Document();
        self::assertEquals(0, $document->data()->count());
        self::assertFalse($document->shouldBeHandledAsCollection());
    }

    public function testCollectionDocument()
    {
        $document = new Document([$this->createMock(ResourceInterface::class)]);
        self::assertEquals(1, $document->data()->count());
        self::assertTrue($document->shouldBeHandledAsCollection());
    }

    public function testEmptyCollectionDocument()
    {
        $document = new Document([]);
        self::assertEquals(0, $document->data()->count());
        self::assertTrue($document->shouldBeHandledAsCollection());
    }

    public function testResourceCollectionDocument()
    {
        $document = new Document($this->createMock(ResourceCollectionInterface::class));
        self::assertEquals(0, $document->data()->count());
        self::assertTrue($document->shouldBeHandledAsCollection());
    }

    public function testEmptyErrorDocument()
    {
        $document = new Document();
        self::assertEquals(0, $document->errors()->count());
        self::assertFalse($document->shouldBeHandledAsCollection());
    }

    public function testHttpStatus()
    {
        $document = new Document();

        self::assertEquals(DocumentInterface::HTTP_OK, $document->httpStatus());

        $document->withHttpStatus(500);
        self::assertEquals(500, $document->httpStatus());
    }
}
