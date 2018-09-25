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
    public function testHas(): void
    {
        $collection = new RelationshipCollection($this->getResources());
        self::assertTrue($collection->has('a'));
        self::assertTrue($collection->has('b'));
        self::assertFalse($collection->has('test'));
    }

    public function testGet(): void
    {
        $collection = new RelationshipCollection($this->getResources());
        /** @noinspection UnnecessaryAssertionInspection */
        self::assertInstanceOf(
            RelationshipInterface::class,
            $collection->get('a')
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetInvalid(): void
    {
        $collection = new RelationshipCollection($this->getResources());
        $collection->get('test');
    }

    public function testSet(): void
    {
        $collection = new RelationshipCollection($this->getResources());
        self::assertFalse($collection->has('test'));
        /** @var RelationshipInterface $relationship */
        $relationship = $this->createConfiguredMock(
            RelationshipInterface::class,
            ['name' => 'test']
        );
        $collection->set(
            $relationship
        );

        self::assertTrue($collection->has('test'));
    }

    public function testRemove(): void
    {
        $collection = new RelationshipCollection($this->getResources());
        self::assertTrue($collection->has('a'));

        $collection->remove('a');

        self::assertFalse($collection->has('a'));
    }


    public function testRemoveElement(): void
    {
        $collection = new RelationshipCollection($this->getResources());
        self::assertTrue($collection->has('a'));
        /** @var RelationshipInterface $relationship */
        $relationship = $this->createConfiguredMock(
            RelationshipInterface::class,
            ['name' => 'a']
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
                ['name' => 'a']
            ),
            $this->createConfiguredMock(
                RelationshipInterface::class,
                ['name' => 'b']
            ),
        ];
    }
}
