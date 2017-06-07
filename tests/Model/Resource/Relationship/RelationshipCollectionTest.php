<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Resource\Relationship;

use Enm\JsonApi\Model\Resource\Relationship\RelationshipCollection;
use Enm\JsonApi\Model\Resource\Relationship\RelationshipInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class RelationshipCollectionTest extends TestCase
{
    public function testHas()
    {
        $collection = new RelationshipCollection($this->getResources());
        self::assertTrue($collection->has('a'));
        self::assertTrue($collection->has('b'));
        self::assertFalse($collection->has('test'));
    }

    public function testGet()
    {
        $collection = new RelationshipCollection($this->getResources());
        self::assertInstanceOf(
            RelationshipInterface::class,
            $collection->get('a')
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetInvalid()
    {
        $collection = new RelationshipCollection($this->getResources());
        $collection->get('test');
    }

    public function testSet()
    {
        $collection = new RelationshipCollection($this->getResources());
        self::assertFalse($collection->has('test'));
        /** @var RelationshipInterface $relationship */
        $relationship = $this->createConfiguredMock(
            RelationshipInterface::class,
            ['getType' => RelationshipInterface::TYPE_ONE, 'getName' => 'test']
        );
        $collection->set(
            $relationship
        );

        self::assertTrue($collection->has('test'));
    }

    public function testSetToOne()
    {
        $collection = new RelationshipCollection();

        $collection->createToOne('test');

        self::assertEquals(RelationshipInterface::TYPE_ONE, $collection->get('test')->getType());
    }

    public function testSetToMany()
    {
        $collection = new RelationshipCollection();

        $collection->createToMany('test');

        self::assertEquals(RelationshipInterface::TYPE_MANY, $collection->get('test')->getType());
    }

    public function testRemove()
    {
        $collection = new RelationshipCollection($this->getResources());
        self::assertTrue($collection->has('a'));

        $collection->remove('a');

        self::assertFalse($collection->has('a'));
    }


    public function testRemoveElement()
    {
        $collection = new RelationshipCollection($this->getResources());
        self::assertTrue($collection->has('a'));
        /** @var RelationshipInterface $relationship */
        $relationship = $this->createConfiguredMock(
            RelationshipInterface::class,
            ['getType' => RelationshipInterface::TYPE_ONE, 'getName' => 'a']
        );
        $collection->removeElement($relationship);

        self::assertFalse($collection->has('a'));
    }

    /**
     * @return RelationshipInterface[]
     */
    private function getResources(): array
    {
        return [
            $this->createConfiguredMock(
                RelationshipInterface::class,
                ['getType' => RelationshipInterface::TYPE_ONE, 'getName' => 'a']
            ),
            $this->createConfiguredMock(
                RelationshipInterface::class,
                ['getType' => RelationshipInterface::TYPE_MANY, 'getName' => 'b']
            ),
        ];
    }
}
