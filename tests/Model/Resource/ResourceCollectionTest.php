<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Resource;

use Enm\JsonApi\Model\Resource\ResourceCollection;
use Enm\JsonApi\Model\Resource\ResourceInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ResourceCollectionTest extends TestCase
{
    public function testHas()
    {
        $collection = new ResourceCollection($this->getResources());
        self::assertTrue($collection->has('test', '1'));
        self::assertTrue($collection->has('test', '2'));
        self::assertFalse($collection->has('test', '3'));
    }

    public function testGet()
    {
        $collection = new ResourceCollection($this->getResources());
        self::assertInstanceOf(
            ResourceInterface::class,
            $collection->get('test', '1')
        );
    }

    public function testFirst()
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
    public function testFirstEmptyCollection()
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
    public function testFirstMissingType()
    {
        $collection = new ResourceCollection($this->getResources());
        self::assertInstanceOf(
            ResourceInterface::class,
            $collection->first('invalid')
        );
    }

    /**
     * @expectedException \Enm\JsonApi\Exception\ResourceNotFoundJsonApiException
     */
    public function testGetInvalid()
    {
        $collection = new ResourceCollection($this->getResources());
        $collection->get('test', '3');
    }

    public function testSet()
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

    public function testRemove()
    {
        $collection = new ResourceCollection($this->getResources());
        self::assertTrue($collection->has('test', '2'));

        $collection->remove('test', '2');

        self::assertFalse($collection->has('test', '2'));
    }


    public function testRemoveElement()
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

    public function testCreateResource()
    {
        $collection = new ResourceCollection();
        $collection->createResource('test', 'test-1');
        self::assertInstanceOf(ResourceInterface::class, $collection->get('test', 'test-1'));
    }
}
