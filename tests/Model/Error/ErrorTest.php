<?php
declare(strict_types = 1);

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
        self::assertEquals('Test Error', $error->getTitle());
        self::assertEquals(500, $error->getStatus());
    }
    
    public function testConfiguredError()
    {
        $error = new Error(
          400,
          'Invalid Request',
          'Invalid Parameter "name" given',
          'invalid_request'
        );
        
        $error->metaInformations()->set('test', 'test');
        
        self::assertEquals('invalid_request', $error->getCode());
        
        self::assertEquals(400, $error->getStatus());
        
        self::assertEquals('Invalid Request', $error->getTitle());
        
        self::assertEquals(
          'Invalid Parameter "name" given',
          $error->getDetail()
        );
        
        self::assertEquals('test', $error->metaInformations()->getRequired('test'));
    }
    
    public function testErrorFromException()
    {
        $error = Error::createFromException(new \Exception('Test', 13));
        
        self::assertEquals('13', $error->getCode());
        self::assertEquals('Test', $error->getTitle());
        self::assertEquals(500, $error->getStatus());
    }
    
    public function testErrorFromExceptionWithDebug()
    {
        $error = Error::createFromException(new \Exception('Test'), true);
        
        self::assertEquals('', $error->getCode());
        self::assertEquals('Test', $error->getTitle());
        self::assertEquals(500, $error->getStatus());
        self::assertArrayHasKey('file', $error->metaInformations()->all());
    }
}
