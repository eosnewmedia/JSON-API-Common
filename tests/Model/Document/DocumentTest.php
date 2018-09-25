<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Document;

use Enm\JsonApi\Model\Document\Document;
use Enm\JsonApi\Model\Resource\ResourceCollectionInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class DocumentTest extends TestCase
{
    public function testResourceDocument(): void
    {
        $document = new Document($this->createMock(ResourceInterface::class));
        self::assertEquals(1, $document->data()->count());
        self::assertFalse($document->shouldBeHandledAsCollection());
    }

    public function testEmptyResourceDocument(): void
    {
        $document = new Document();
        self::assertEquals(0, $document->data()->count());
        self::assertFalse($document->shouldBeHandledAsCollection());
    }

    public function testCollectionDocument(): void
    {
        $document = new Document([$this->createMock(ResourceInterface::class)]);
        self::assertEquals(1, $document->data()->count());
        self::assertTrue($document->shouldBeHandledAsCollection());
    }

    public function testEmptyCollectionDocument(): void
    {
        $document = new Document([]);
        self::assertEquals(0, $document->data()->count());
        self::assertTrue($document->shouldBeHandledAsCollection());
    }

    public function testResourceCollectionDocument(): void
    {
        $document = new Document($this->createMock(ResourceCollectionInterface::class));
        self::assertEquals(0, $document->data()->count());
        self::assertTrue($document->shouldBeHandledAsCollection());
    }

    public function testEmptyErrorDocument(): void
    {
        $document = new Document();
        self::assertEquals(0, $document->errors()->count());
        self::assertFalse($document->shouldBeHandledAsCollection());
    }
}
