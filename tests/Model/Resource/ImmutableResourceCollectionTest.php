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
    /**
     * @expectedException \LogicException
     */
    public function testSet(): void
    {
        $collection = new ImmutableResourceCollection();
        /** @var ResourceInterface $resource */
        $resource = $this->createMock(ResourceInterface::class);
        $collection->set($resource);
    }

    /**
     * @expectedException \LogicException
     */
    public function testRemove(): void
    {
        $collection = new ImmutableResourceCollection();
        $collection->remove('test', '1');
    }

    /**
     * @expectedException \LogicException
     */
    public function testRemoveElement(): void
    {
        $collection = new ImmutableResourceCollection();
        /** @var ResourceInterface $resource */
        $resource = $this->createMock(ResourceInterface::class);
        $collection->removeElement($resource);
    }
}
