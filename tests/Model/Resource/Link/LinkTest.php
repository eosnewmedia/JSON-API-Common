<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Resource\Link;

use Enm\JsonApi\Model\Resource\Link\Link;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class LinkTest extends TestCase
{
    public function testLink()
    {
        $link = new Link('about', 'http://jsonapi.org');
        $link->metaInformations()->set('test', 'test');

        self::assertEquals('about', $link->getName());
        self::assertEquals('http://jsonapi.org', $link->getHref());
        self::assertArrayHasKey('test', $link->metaInformations()->all());
    }

    public function testDuplicateLink()
    {
        $link = new Link('about', 'http://jsonapi.org');
        $link->metaInformations()->set('test', 'test');

        self::assertNotSame($link, $link->duplicate());
        self::assertNotSame($link->metaInformations(), $link->duplicate()->metaInformations());
        self::assertNotSame($link->duplicate(), $link->duplicate());
        self::assertEquals('test', $link->duplicate('test')->getName());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionOnInvalidName()
    {
        new Link('', 'http://jsonapi.org');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionOnInvalidUrl()
    {
        new Link('about', 'jsonapi.org');
    }
}
