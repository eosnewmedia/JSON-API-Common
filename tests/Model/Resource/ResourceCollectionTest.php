<?php
/** @noinspection UnnecessaryAssertionInspection */
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Resource;

use Enm\JsonApi\Exception\ResourceNotFoundException;
use Enm\JsonApi\Model\Resource\ResourceCollection;
use Enm\JsonApi\Model\Resource\ResourceInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ResourceCollectionTest extends TestCase
{
    public function testHas(): void
    {
        $collection = new ResourceCollection($this->getResources());
        self::assertTrue($collection->has('test', '1'));
        self::assertTrue($collection->has('test', '2'));
        self::assertFalse($collection->has('test', '3'));
    }

    public function testGet(): void
    {
        $collection = new ResourceCollection($this->getResources());
        try {
            self::assertInstanceOf(
                ResourceInterface::class,
                $collection->get('test', '1')
            );
        } catch (ResourceNotFoundException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testFirst(): void
    {
        $collection = new ResourceCollection($this->getResources());
        self::assertInstanceOf(
            ResourceInterface::class,
            $collection->first('test')
        );
    }

    /**
     * @expectedException \LogicException
     */
    public function testFirstEmptyCollection(): void
    {
        $collection = new ResourceCollection();
        self::assertInstanceOf(
            ResourceInterface::class,
            $collection->first('test')
        );
    }

    /**
     * @expectedException \LogicException
     */
    public function testFirstMissingType(): void
    {
        $collection = new ResourceCollection($this->getResources());
        self::assertInstanceOf(
            ResourceInterface::class,
            $collection->first('invalid')
        );
    }

    /**
     * @expectedException \Enm\JsonApi\Exception\ResourceNotFoundException
     */
    public function testGetInvalid(): void
    {
        $collection = new ResourceCollection($this->getResources());
        $collection->get('test', '3');
    }

    public function testSet(): void
    {
        $collection = new ResourceCollection($this->getResources());
        self::assertFalse($collection->has('test', '3'));
        /** @var ResourceInterface $resource */
        $resource = $this->createConfiguredMock(
            ResourceInterface::class,
            ['type' => 'test', 'id' => '3']
        );
        $collection->set($resource);

        self::assertTrue($collection->has('test', '3'));
    }

    public function testRemove(): void
    {
        $collection = new ResourceCollection($this->getResources());
        self::assertTrue($collection->has('test', '2'));

        $collection->remove('test', '2');

        self::assertFalse($collection->has('test', '2'));
    }


    public function testRemoveElement(): void
    {
        $collection = new ResourceCollection($this->getResources());
        self::assertTrue($collection->has('test', '2'));

        /** @var ResourceInterface $resource */
        $resource = $this->createConfiguredMock(
            ResourceInterface::class,
            ['type' => 'test', 'id' => '2']
        );

        $collection->removeElement($resource);

        self::assertFalse($collection->has('test', '2'));
    }

    /**
     * @return ResourceInterface[]
     */
    private function getResources(): array
    {
        return [
            $this->createConfiguredMock(
                ResourceInterface::class,
                ['type' => 'test', 'id' => '1']
            ),
            $this->createConfiguredMock(
                ResourceInterface::class,
                ['type' => 'test', 'id' => '2']
            ),
        ];
    }
}
