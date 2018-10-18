<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Common;

use Enm\JsonApi\Model\Common\KeyValueCollection;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class KeyValueCollectionTest extends TestCase
{
    public function testAll(): void
    {
        $collection = new KeyValueCollection(['test' => 'test']);
        self::assertArrayHasKey('test', $collection->all());
    }

    public function testCount(): void
    {
        $collection = new KeyValueCollection(['test' => 'test']);
        self::assertCount(1, $collection);
    }

    public function testIsEmpty(): void
    {
        $collection = new KeyValueCollection(['test' => 'test']);
        self::assertFalse($collection->isEmpty());
    }

    public function testHas(): void
    {
        $collection = new KeyValueCollection(['test' => 'test']);
        self::assertTrue($collection->has('test'));
    }

    public function testGetRequired(): void
    {
        $collection = new KeyValueCollection(['test' => 'test']);
        self::assertEquals('test', $collection->getRequired('test'));
    }

    public function testGetOptional(): void
    {
        $collection = new KeyValueCollection();
        self::assertEquals('test', $collection->getOptional('test', 'test'));
    }

    public function testCreateSubCollection(): void
    {
        $collection = new KeyValueCollection(['test' => ['abc' => 'abc']]);

        self::assertEquals('abc', $collection->createSubCollection('test')->getRequired('abc'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateSubCollectionInvalid(): void
    {
        $collection = new KeyValueCollection(['test' => 'abc']);

        $collection->createSubCollection('test');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateSubCollectionRequired(): void
    {
        $collection = new KeyValueCollection();
        $collection->createSubCollection('test');
    }

    public function testCreateSubCollectionOptional(): void
    {
        $collection = new KeyValueCollection();

        self::assertCount(0, $collection->createSubCollection('test', false)->all());
    }

    public function testMergeCollection(): void
    {
        $collection = new KeyValueCollection(['test' => 'test']);

        self::assertEquals('test', $collection->getRequired('test'));

        $collection->mergeCollection(new KeyValueCollection(['test' => 'abc', 'test2' => 'def']));

        self::assertEquals('abc', $collection->getRequired('test'));
        self::assertEquals('def', $collection->getRequired('test2'));
    }

    public function testMergeCollectionNoOverwrite(): void
    {
        $collection = new KeyValueCollection(['test' => 'test']);

        self::assertEquals('test', $collection->getRequired('test'));

        $collection->mergeCollection(new KeyValueCollection(['test' => 'abc', 'test2' => 'def']), false);

        self::assertEquals('test', $collection->getRequired('test'));
        self::assertEquals('def', $collection->getRequired('test2'));
    }

    public function testMerge(): void
    {
        $collection = new KeyValueCollection(['test' => 'test']);

        self::assertEquals('test', $collection->getRequired('test'));

        $collection->merge(['test' => 'abc', 'test2' => 'def']);

        self::assertEquals('abc', $collection->getRequired('test'));
        self::assertEquals('def', $collection->getRequired('test2'));
    }


    public function testMergeNoOverwrite(): void
    {
        $collection = new KeyValueCollection(['test' => 'test']);

        self::assertEquals('test', $collection->getRequired('test'));

        $collection->merge(['test' => 'abc', 'test2' => 'def'], false);

        self::assertEquals('test', $collection->getRequired('test'));
        self::assertEquals('def', $collection->getRequired('test2'));
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetInvalid(): void
    {
        $collection = new KeyValueCollection(['test' => 'test']);
        $collection->getRequired('abc');
    }

    public function testSet(): void
    {
        $collection = new KeyValueCollection(['test' => 'test']);
        self::assertFalse($collection->has('abc'));
        $collection->set('abc', 'abc');
        self::assertTrue($collection->has('abc'));
    }

    public function testSetCollection(): void
    {
        $collection = new KeyValueCollection(['test' => 'test']);
        self::assertFalse($collection->has('abc'));
        $collection->set('abc', new KeyValueCollection(['test' => 'test']));
        self::assertTrue($collection->has('abc'));
        self::assertArrayHasKey('test', $collection->getRequired('abc'));
    }

    public function testRemove(): void
    {
        $collection = new KeyValueCollection(['test' => 'test']);
        self::assertTrue($collection->has('test'));
        $collection->remove('test');
        self::assertFalse($collection->has('test'));
    }

    public function testRemoveInvalid(): void
    {
        $collection = new KeyValueCollection(['test' => 'test']);
        $collection->remove('abc');

        self::assertTrue($collection->has('test'));
    }
}
