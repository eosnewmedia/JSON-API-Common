<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Resource\Link;

use Enm\JsonApi\Model\Resource\Link\LinkCollection;
use Enm\JsonApi\Model\Resource\Link\LinkInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class LinkCollectionTest extends TestCase
{
    public function testHas(): void
    {
        $collection = new LinkCollection($this->getLinks());
        self::assertTrue($collection->has('a'));
    }

    public function testGet(): void
    {
        $collection = new LinkCollection($this->getLinks());
        /** @noinspection UnnecessaryAssertionInspection */
        self::assertInstanceOf(LinkInterface::class, $collection->get('a'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetInvalid(): void
    {
        $collection = new LinkCollection($this->getLinks());
        $collection->get('c');
    }

    public function testSet(): void
    {
        $collection = new LinkCollection($this->getLinks());
        self::assertFalse($collection->has('c'));
        /** @var LinkInterface $link */
        $link = $this->createConfiguredMock(
            LinkInterface::class, ['name' => 'c']
        );
        $collection->set(
            $link
        );
        self::assertTrue($collection->has('c'));
    }

    public function testRemove(): void
    {
        $collection = new LinkCollection($this->getLinks());
        self::assertTrue($collection->has('a'));
        $collection->remove('a');
        self::assertFalse($collection->has('a'));
    }

    public function testRemoveElement(): void
    {
        $collection = new LinkCollection($this->getLinks());
        self::assertTrue($collection->has('a'));
        /** @var LinkInterface $link */
        $link = $this->createConfiguredMock(
            LinkInterface::class, ['name' => 'a']
        );
        $collection->removeElement($link);
        self::assertFalse($collection->has('a'));
    }

    public function testCreateLink(): void
    {
        $collection = new LinkCollection();
        $collection->createLink('test', 'http://example.com');

        self::assertEquals('http://example.com', $collection->get('test')->href());
    }

    /**
     * @return LinkInterface[]
     */
    private function getLinks(): array
    {
        return [
            $this->createConfiguredMock(LinkInterface::class, ['name' => 'a']),
            $this->createConfiguredMock(LinkInterface::class, ['name' => 'b']),
        ];
    }
}
