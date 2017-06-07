<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Resource;

use Enm\JsonApi\Model\Resource\JsonResource;
use Enm\JsonApi\Model\Resource\Link\Link;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class JsonResourceTest extends TestCase
{
    public function testJsonResource()
    {
        $resource = new JsonResource('test', '1', ['attr' => 'test']);
        self::assertEquals('test', $resource->attributes()->getRequired('attr'));
        self::assertEquals('test', $resource->getType());
        self::assertEquals('1', $resource->getId());
        self::assertEquals(0, $resource->relationships()->count());
        self::assertEquals(0, $resource->links()->count());
        self::assertEquals(0, $resource->metaInformations()->count());
    }

    public function testDuplicateJsonResource()
    {
        $resource = new JsonResource('test', '1', ['attr' => 'test']);
        $resource->links()->set(new Link('test', 'http://test.de'));
        $resource->relationships()->createToMany('test');

        $duplicate = $resource->duplicate();


        self::assertNotSame($resource, $duplicate);
        self::assertNotSame($resource->attributes(), $duplicate->attributes());
        self::assertNotSame($resource->metaInformations(), $duplicate->metaInformations());
        self::assertNotSame($resource->links(), $duplicate->links());
        self::assertNotSame($resource->links()->get('test'), $duplicate->links()->get('test'));
        self::assertNotSame($resource->relationships(), $duplicate->relationships());
        self::assertNotSame($resource->relationships()->get('test'), $duplicate->relationships()->get('test'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testJsonResourceEmptyType()
    {
        new JsonResource('', '1');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testJsonResourceEmptyId()
    {
        new JsonResource('test', '');
    }
}
