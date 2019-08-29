<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Request;

use Enm\JsonApi\Model\Request\Request;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class RequestTest extends TestCase
{
    public function testRequest(): void
    {
        try {
            $request = new Request(
                'GET',
                new Uri('/index.php/api/examples/example-1?include=tests,tests.user&fields[user]=username,birthday&page[offset]=0&page[limit]=10&sort=-createdAt&filter[test]=test'),
                null,
                'api'
            );
        } catch (\Exception $e) {
            $this->fail($e->getMessage() . ' (' . $e->getFile() . ', ' . $e->getLine() . ')');
            return;
        }

        self::assertEquals('examples', $request->type());
        self::assertEquals('example-1', $request->id());
        self::assertNull($request->relationship());
        self::assertTrue($request->requestsAttributes());
        self::assertTrue($request->requestsMetaInformation());
        self::assertTrue($request->requestsRelationships());
        self::assertTrue($request->requestsInclude('tests'));
        self::assertTrue($request->requestsInclude('tests.user'));
        self::assertFalse($request->requestsInclude('examples'));
        self::assertTrue($request->requestsField('examples', 'test'));
        self::assertTrue($request->requestsField('user', 'username'));
        self::assertTrue($request->requestsField('user', 'birthday'));
        self::assertFalse($request->requestsField('user', 'password'));
        self::assertEquals('test', $request->filterValue('test'));
        self::assertEquals(['createdAt' => 'desc'], $request->order());
        self::assertEquals('0', $request->paginationValue('offset'));
        self::assertEquals('10', $request->paginationValue('limit'));
    }

    /**
     * @expectedException \Enm\JsonApi\Exception\BadRequestException
     */
    public function testRequestInvalidType(): void
    {
        new Request(
            'GET',
            new Uri('/index.php/api'),
            null,
            'api'
        );
    }
}
