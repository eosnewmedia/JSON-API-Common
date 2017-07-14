<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Request;


use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;
use Enm\JsonApi\Model\Request\FetchRequest;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class FetchRequestTest extends TestCase
{
    public function testResourceRequest()
    {
        $request = new FetchRequest('test');
        $request->field('test', 'example');
        $request->include('test_b');
        $request->include('test_b.abc');

        self::assertArraySubset(['test' => ['example']], $request->fields());
        self::assertArraySubset(['test_b', 'test_b.abc'], $request->includes());

        self::assertInstanceOf(KeyValueCollectionInterface::class, $request->sorting());
        self::assertInstanceOf(KeyValueCollectionInterface::class, $request->pagination());
        self::assertInstanceOf(KeyValueCollectionInterface::class, $request->filter());
    }
}
