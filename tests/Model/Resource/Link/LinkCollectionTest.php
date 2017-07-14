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
    public function testHas()
    {
        $collection = new LinkCollection($this->getLinks());
        self::assertTrue($collection->has('a'));
    }

    public function testGet()
    {
        $collection = new LinkCollection($this->getLinks());
        self::assertInstanceOf(LinkInterface::class, $collection->get('a'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetInvalid()
    {
        $collection = new LinkCollection($this->getLinks());
        $collection->get('c');
    }

    public function testSet()
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

    public function testRemove()
    {
        $collection = new LinkCollection($this->getLinks());
        self::assertTrue($collection->has('a'));
        $collection->remove('a');
        self::assertFalse($collection->has('a'));
    }

    public function testRemoveElement()
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

    public function testCreateLink()
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
