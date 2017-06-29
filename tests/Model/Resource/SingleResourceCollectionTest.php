<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Resource;

use Enm\JsonApi\Model\Resource\ResourceInterface;
use Enm\JsonApi\Model\Resource\SingleResourceCollection;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class SingleResourceCollectionTest extends TestCase
{

    public function testSet()
    {
        $collection = new SingleResourceCollection();

        self::assertEquals(0, $collection->count());

        /** @var ResourceInterface $resource */
        $resource = $this->createMock(ResourceInterface::class);
        $collection->set($resource);

        self::assertEquals(1, $collection->count());
    }

    /**
     * @expectedException \LogicException
     */
    public function testConstructMultiple()
    {
        new SingleResourceCollection(
            [
                $this->createMock(ResourceInterface::class),
                $this->createMock(ResourceInterface::class)
            ]
        );
    }

    /**
     * @expectedException \LogicException
     */
    public function testSetMultiple()
    {
        $collection = new SingleResourceCollection();
        /** @var ResourceInterface $resource */
        $resource = $this->createMock(ResourceInterface::class);
        $collection->set($resource);
        $collection->set($resource);
    }
}
