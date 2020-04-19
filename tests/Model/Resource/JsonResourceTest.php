<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Resource;

use Enm\JsonApi\Model\Resource\JsonResource;
use Enm\JsonApi\Model\Resource\Link\Link;
use Enm\JsonApi\Model\Resource\Relationship\Relationship;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class JsonResourceTest extends TestCase
{
    public function testJsonResource(): void
    {
        $resource = new JsonResource('test', '1', ['attr' => 'test']);
        self::assertEquals('test', $resource->attributes()->getRequired('attr'));
        self::assertEquals('test', $resource->type());
        self::assertEquals('1', $resource->id());
        self::assertEquals(0, $resource->relationships()->count());
        self::assertEquals(0, $resource->links()->count());
        self::assertEquals(0, $resource->metaInformation()->count());
        self::assertEquals(0, $resource->relatedMetaInformation()->count());
    }

    public function testJsonResourceWithoutId(): void
    {
        $resource = new JsonResource('test', null, ['attrNull' => 'testNull']);
        self::assertNull($resource->id());
        self::assertEquals('testNull', $resource->attributes()->getRequired('attrNull'));
        self::assertEquals('test', $resource->type());
    }

    public function testDuplicateJsonResource(): void
    {
        $resource = new JsonResource('test', '1', ['attr' => 'test']);
        $resource->links()->set(new Link('test', 'http://test.de'));
        $resource->relationships()->set(new Relationship('test'));

        $duplicate = $resource->duplicate();


        self::assertNotSame($resource, $duplicate);
        self::assertNotSame($resource->attributes(), $duplicate->attributes());
        self::assertNotSame($resource->metaInformation(), $duplicate->metaInformation());
        self::assertNotSame($resource->links(), $duplicate->links());
        self::assertNotSame($resource->links()->get('test'), $duplicate->links()->get('test'));
        self::assertNotSame($resource->relationships(), $duplicate->relationships());
        self::assertNotSame($resource->relationships()->get('test'), $duplicate->relationships()->get('test'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testJsonResourceEmptyType(): void
    {
        new JsonResource('', '1');
    }
}
