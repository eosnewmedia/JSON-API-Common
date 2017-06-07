<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Resource\Relationship;

use Enm\JsonApi\Model\Resource\Link\Link;
use Enm\JsonApi\Model\Resource\Link\LinkInterface;
use Enm\JsonApi\Model\Resource\Relationship\ToManyRelationship;
use Enm\JsonApi\Model\Resource\Relationship\ToOneRelationship;
use Enm\JsonApi\Model\Resource\ResourceInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class RelationshipTest extends TestCase
{

    public function testToOne()
    {
        $relation = new ToOneRelationship(
            'test', $this->createMock(ResourceInterface::class)
        );
        /** @var LinkInterface $link */
        $link = $this->createMock(LinkInterface::class);
        $relation->links()->set($link);
        $relation->metaInformations()->set('test', 1);

        self::assertEquals(1, $relation->related()->count());
        self::assertEquals('test', $relation->getName());
        self::assertEquals(1, $relation->links()->count());
        self::assertEquals(1, $relation->metaInformations()->count());
    }

    public function testEmptyToOne()
    {
        $relation = new ToOneRelationship('test');
        self::assertEquals(0, $relation->related()->count());
    }

    public function testToMany()
    {
        $relation = new ToManyRelationship(
            'test',
            [
                $this->createMock(ResourceInterface::class),
            ]
        );
        self::assertEquals(1, $relation->related()->count());
    }

    public function testEmptyToMany()
    {
        $relation = new ToManyRelationship('test');
        self::assertEquals(0, $relation->related()->count());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidNamedToOne()
    {
        new ToOneRelationship('');
    }

    /**
     * @expectedException \LogicException
     */
    public function testImmutableToOne()
    {
        $relation = new ToOneRelationship('test');
        /** @var ResourceInterface $resource */
        $resource = $this->createConfiguredMock(
            ResourceInterface::class,
            ['getType' => 'test', 'getId' => '1']
        );
        $relation->related()->set($resource);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidNamedToMany()
    {
        new ToManyRelationship('');
    }

    public function testDuplicateToOne()
    {
        $relation = new ToOneRelationship(
            'test',
            $this->createConfiguredMock(
                ResourceInterface::class,
                ['getType' => 'test', 'getId' => '1']
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
        $relation = new ToManyRelationship(
            'test',
            [
                $this->createConfiguredMock(
                    ResourceInterface::class,
                    ['getType' => 'test', 'getId' => '1']
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
}
