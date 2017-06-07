<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Exception;

use Enm\JsonApi\Exception\Exception;
use Enm\JsonApi\Exception\HttpException;
use Enm\JsonApi\Exception\InvalidRequestException;
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
    public function testInvalidRequestException()
    {
        $exception = new InvalidRequestException();
        self::assertInstanceOf(\Exception::class, $exception);
        self::assertInstanceOf(Exception::class, $exception);
        self::assertEquals(400, $exception->getHttpStatus());
        self::assertEquals('Invalid Request!', $exception->getMessage());
        self::assertInstanceOf(
            ErrorInterface::class,
            $exception->createError()
        );
    }

    public function testResourceNotFoundException()
    {
        $exception = new ResourceNotFoundException('test', 'id');
        self::assertInstanceOf(\Exception::class, $exception);
        self::assertInstanceOf(Exception::class, $exception);
        self::assertEquals(404, $exception->getHttpStatus());
        self::assertEquals(
            'Resource "id" of type "test" not found!',
            $exception->getMessage()
        );
        self::assertInstanceOf(
            ErrorInterface::class,
            $exception->createError()
        );
    }

    public function testUnsupportedMediaTypeException()
    {
        $exception = new UnsupportedMediaTypeException();
        self::assertInstanceOf(\Exception::class, $exception);
        self::assertInstanceOf(Exception::class, $exception);
        self::assertEquals(415, $exception->getHttpStatus());
        self::assertEquals('', $exception->getMessage());
        self::assertInstanceOf(
            ErrorInterface::class,
            $exception->createError()
        );
    }

    public function testException()
    {
        $exception = new Exception('Test');
        self::assertInstanceOf(\Exception::class, $exception);
        self::assertEquals(500, $exception->getHttpStatus());

        $error = $exception->createError();
        self::assertInstanceOf(
            ErrorInterface::class,
            $exception->createError()
        );
        self::assertEquals(500, $error->getStatus());
        self::assertEquals('Test', $error->getTitle());
    }

    public function testUnsupportedTypeException()
    {
        $exception = new UnsupportedTypeException('Test');
        self::assertInstanceOf(\Exception::class, $exception);
        self::assertEquals(404, $exception->getHttpStatus());

        $error = $exception->createError();
        self::assertInstanceOf(
            ErrorInterface::class,
            $exception->createError()
        );
        self::assertEquals(404, $error->getStatus());
        self::assertEquals('Resource type "Test" not found', $error->getTitle());
    }

    public function testNotAllowedException()
    {
        $exception = new NotAllowedException('Test');
        self::assertInstanceOf(\Exception::class, $exception);
        self::assertEquals(403, $exception->getHttpStatus());

        $error = $exception->createError();
        self::assertInstanceOf(
            ErrorInterface::class,
            $exception->createError()
        );
        self::assertEquals(403, $error->getStatus());
        self::assertEquals('Test', $error->getTitle());
    }

    public function testHttpException()
    {
        $exception = new HttpException(503, 'Test');
        self::assertInstanceOf(\Exception::class, $exception);
        self::assertEquals(503, $exception->getHttpStatus());

        $error = $exception->createError();
        self::assertInstanceOf(
            ErrorInterface::class,
            $exception->createError()
        );
        self::assertEquals(503, $error->getStatus());
        self::assertEquals('Test', $error->getTitle());
    }
}
