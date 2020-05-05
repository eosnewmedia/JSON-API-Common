<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Exception;

use Enm\JsonApi\Exception\JsonApiException;
use Enm\JsonApi\Exception\HttpException;
use Enm\JsonApi\Exception\BadRequestException;
use Enm\JsonApi\Exception\NotAllowedException;
use Enm\JsonApi\Exception\ResourceNotFoundException;
use Enm\JsonApi\Exception\UnsupportedMediaTypeException;
use Enm\JsonApi\Exception\UnsupportedTypeException;
use Enm\JsonApi\Model\Error\ErrorInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ExceptionTest extends TestCase
{
    public function testInvalidRequestException(): void
    {
        $exception = new BadRequestException();
        self::assertInstanceOf(\Exception::class, $exception);
        self::assertInstanceOf(JsonApiException::class, $exception);
        self::assertEquals(400, $exception->getHttpStatus());
        self::assertEquals('Invalid Request!', $exception->getMessage());
        self::assertInstanceOf(ErrorInterface::class, $exception->errors()->first());
        self::assertEquals('Invalid Request!', $exception->errors()->first()->title());
    }

    public function testResourceNotFoundException(): void
    {
        $exception = new ResourceNotFoundException('test', 'id');
        self::assertInstanceOf(\Exception::class, $exception);
        self::assertInstanceOf(JsonApiException::class, $exception);
        self::assertEquals(404, $exception->getHttpStatus());
        self::assertEquals(
            'Resource "id" of type "test" not found!',
            $exception->getMessage()
        );
        self::assertInstanceOf(ErrorInterface::class, $exception->errors()->first());
        self::assertEquals(
            'Resource "id" of type "test" not found!',
            $exception->errors()->first()->title()
        );
    }

    public function testUnsupportedMediaTypeException(): void
    {
        $exception = new UnsupportedMediaTypeException('text/html');
        self::assertInstanceOf(\Exception::class, $exception);
        self::assertInstanceOf(JsonApiException::class, $exception);
        self::assertEquals(415, $exception->getHttpStatus());
        self::assertEquals('Invalid content type: text/html', $exception->getMessage());
        self::assertInstanceOf(ErrorInterface::class, $exception->errors()->first());
        self::assertEquals('Invalid content type: text/html', $exception->errors()->first()->title());
    }

    public function testException(): void
    {
        $exception = new JsonApiException('Test');
        self::assertInstanceOf(\Exception::class, $exception);
        self::assertEquals(500, $exception->getHttpStatus());

        $error = $exception->errors()->first();
        self::assertInstanceOf(ErrorInterface::class, $error);
        self::assertEquals(500, $error->status());
        self::assertEquals('Test', $error->title());
    }

    public function testUnsupportedTypeException(): void
    {
        $exception = new UnsupportedTypeException('Test');
        self::assertInstanceOf(\Exception::class, $exception);
        self::assertEquals(404, $exception->getHttpStatus());

        $error = $exception->errors()->first();
        self::assertInstanceOf(ErrorInterface::class, $error);
        self::assertEquals(404, $error->status());
        self::assertEquals('Resource type "Test" not found', $error->title());
    }

    public function testNotAllowedException(): void
    {
        $exception = new NotAllowedException('Test');
        self::assertInstanceOf(\Exception::class, $exception);
        self::assertEquals(403, $exception->getHttpStatus());

        $error = $exception->errors()->first();
        self::assertInstanceOf(ErrorInterface::class, $error);
        self::assertEquals(403, $error->status());
        self::assertEquals('Test', $error->title());
    }

    public function testHttpException(): void
    {
        $exception = new HttpException(503, 'Test');
        self::assertInstanceOf(\Exception::class, $exception);
        self::assertEquals(503, $exception->getHttpStatus());

        $error = $exception->errors()->first();
        self::assertInstanceOf(ErrorInterface::class, $error);
        self::assertEquals(503, $error->status());
        self::assertEquals('Test', $error->title());
    }
}