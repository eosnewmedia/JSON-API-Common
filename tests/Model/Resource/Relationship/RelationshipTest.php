<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Resource\Relationship;

use Enm\JsonApi\Model\Resource\Link\Link;
use Enm\JsonApi\Model\Resource\Link\LinkInterface;
use Enm\JsonApi\Model\Resource\Relationship\Relationship;
use Enm\JsonApi\Model\Resource\ResourceCollection;
use Enm\JsonApi\Model\Resource\ResourceInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class RelationshipTest extends TestCase
{

    public function testToOne()
    {
        $relation = new Relationship(
            'test', $this->createMock(ResourceInterface::class)
        );
        /** @var LinkInterface $link */
        $link = $this->createMock(LinkInterface::class);
        $relation->links()->set($link);
        $relation->metaInformation()->set('test', 1);

        self::assertEquals(1, $relation->related()->count());
        self::assertEquals('test', $relation->name());
        self::assertEquals(1, $relation->links()->count());
        self::assertEquals(1, $relation->metaInformation()->count());
    }

    public function testEmptyToOne()
    {
        $relation = new Relationship('test');
        self::assertEquals(0, $relation->related()->count());
    }

    public function testToMany()
    {
        $relation = new Relationship(
            'test',
            [
                $this->createMock(ResourceInterface::class),
            ]
        );
        self::assertEquals(1, $relation->related()->count());
    }

    public function testEmptyToMany()
    {
        $relation = new Relationship('test');
        self::assertEquals(0, $relation->related()->count());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidNamedRelationship()
    {
        new Relationship('');
    }

    /**
     * @expectedException \LogicException
     */
    public function testImmutableToOne()
    {
        $relation = new Relationship('test', $this->createMock(ResourceInterface::class));
        /** @var ResourceInterface $resource */
        $resource = $this->createConfiguredMock(
            ResourceInterface::class,
            ['type' => 'test', 'id' => '1']
        );
        $relation->related()->set($resource);
    }

    public function testDuplicateToOne()
    {
        $relation = new Relationship(
            'test',
            $this->createConfiguredMock(
                ResourceInterface::class,
                ['type' => 'test', 'id' => '1']
            )
        );
        $relation->links()->set(new Link('test', 'http://example.com'));

        self::assertNotSame($relation, $relation->duplicate());
        self::assertNotSame($relation->related(), $relation->duplicate()->related());
        self::assertNotSame($relation->related()->first(), $relation->duplicate()->related()->first());
        self::assertNotSame($relation->links(), $relation->duplicate()->links());
        self::assertNotSame($relation->links()->get('test'), $relation->duplicate()->links()->get('test'));
    }

    public function testDuplicateToMany()
    {
        $relation = new Relationship(
            'test',
            [
                $this->createConfiguredMock(
                    ResourceInterface::class,
                    ['type' => 'test', 'id' => '1']
                )
            ]
        );
        $relation->links()->set(new Link('test', 'http://example.com'));

        self::assertNotSame($relation, $relation->duplicate());
        self::assertNotSame($relation->related(), $relation->duplicate()->related());
        self::assertNotSame($relation->related()->first(), $relation->duplicate()->related()->first());
        self::assertNotSame($relation->links(), $relation->duplicate()->links());
        self::assertNotSame($relation->links()->get('test'), $relation->duplicate()->links()->get('test'));
    }

    public function testWithResourceCollection()
    {
        $collection = new ResourceCollection();

        $relation = new Relationship('test', $collection);

        self::assertSame($collection, $relation->related());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidRelationshipData()
    {
        new Relationship('test', 'test');
    }
}
