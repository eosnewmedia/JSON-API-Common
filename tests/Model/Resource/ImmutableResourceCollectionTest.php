<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Resource;

use Enm\JsonApi\Model\Resource\ImmutableResourceCollection;
use Enm\JsonApi\Model\Resource\ResourceInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ImmutableResourceCollectionTest extends TestCase
{
    public function testIsEmpty()
    {
        $collection = new ImmutableResourceCollection();
        self::assertTrue($collection->isEmpty());
    }

    public function testCount()
    {
        $collection = new ImmutableResourceCollection();
        self::assertEquals(0, $collection->count());
    }

    public function testAll()
    {
        $collection = new ImmutableResourceCollection();
        self::assertCount(0, $collection->all());
    }

    public function testHas()
    {
        $collection = new ImmutableResourceCollection($this->getResources());
        self::assertTrue($collection->has('test', '1'));
    }

    public function testGet()
    {
        $collection = new ImmutableResourceCollection($this->getResources());
        self::assertInstanceOf(
            ResourceInterface::class, $collection->get('test', '1')
        );
    }

    public function testGetFirst()
    {
        $collection = new ImmutableResourceCollection($this->getResources());
        self::assertInstanceOf(
            ResourceInterface::class, $collection->first('test')
        );
    }

    /**
     * @expectedException \LogicException
     */
    public function testSet()
    {
        $collection = new ImmutableResourceCollection();
        /** @var ResourceInterface $resource */
        $resource = $this->createMock(ResourceInterface::class);
        $collection->set($resource);
    }

    /**
     * @expectedException \LogicException
     */
    public function testRemove()
    {
        $collection = new ImmutableResourceCollection();
        $collection->remove('test', '1');
    }

    /**
     * @expectedException \LogicException
     */
    public function testRemoveElement()
    {
        $collection = new ImmutableResourceCollection();
        /** @var ResourceInterface $resource */
        $resource = $this->createMock(ResourceInterface::class);
        $collection->removeElement($resource);
    }


    /**
     * @return ResourceInterface[]
     */
    private function getResources(): array
    {
        return [
            $this->createConfiguredMock(
                ResourceInterface::class,
                ['getType' => 'test', 'getId' => '1']
            ),
            $this->createConfiguredMock(
                ResourceInterface::class,
                ['getType' => 'test', 'getId' => '2']
            ),
        ];
    }
}
