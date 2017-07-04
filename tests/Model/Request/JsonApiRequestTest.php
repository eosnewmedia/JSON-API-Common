<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Request;

use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;
use Enm\JsonApi\Model\Request\JsonApiRequest;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class JsonApiRequestTest extends TestCase
{
    public function testJsonApiRequest()
    {
        $request = new JsonApiRequest('test', 'test-1');

        self::assertEquals('test', $request->type());
        self::assertEquals('test-1', $request->id());
        self::assertTrue($request->containsId());
        self::assertInstanceOf(KeyValueCollectionInterface::class, $request->headers());
    }

    public function testJsonApiRequestWithoutId()
    {
        $request = new JsonApiRequest('test');

        self::assertEquals('test', $request->type());
        self::assertEquals('', $request->id());
        self::assertFalse($request->containsId());
        self::assertInstanceOf(KeyValueCollectionInterface::class, $request->headers());
    }

    /**
     * @expectedException \Enm\JsonApi\Exception\BadRequestException
     */
    public function testJsonApiRequestInvalidType()
    {
        new JsonApiRequest('');
    }
}
