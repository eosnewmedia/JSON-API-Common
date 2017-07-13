<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Error;

use Enm\JsonApi\Model\Error\Error;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ErrorTest extends TestCase
{
    public function testSimpleError()
    {
        $error = new Error(500, 'Test Error');
        self::assertEquals('Test Error', $error->title());
        self::assertEquals(500, $error->status());
    }

    public function testConfiguredError()
    {
        $error = new Error(
            400,
            'Invalid Request',
            'Invalid Parameter "name" given',
            'invalid_request'
        );

        $error->metaInformation()->set('test', 'test');

        self::assertEquals('invalid_request', $error->code());

        self::assertEquals(400, $error->status());

        self::assertEquals('Invalid Request', $error->title());

        self::assertEquals(
            'Invalid Parameter "name" given',
            $error->detail()
        );

        self::assertEquals('test', $error->metaInformation()->getRequired('test'));
    }

    public function testErrorFromException()
    {
        $error = Error::createFrom(new \Exception('Test', 13));

        self::assertEquals('13', $error->code());
        self::assertEquals('Test', $error->title());
        self::assertEquals(500, $error->status());
    }

    public function testErrorFromExceptionWithDebug()
    {
        $error = Error::createFrom(new \Exception('Test'), true);

        self::assertEquals('', $error->code());
        self::assertEquals('Test', $error->title());
        self::assertEquals(500, $error->status());
        self::assertArrayHasKey('file', $error->metaInformation()->all());
    }
}
