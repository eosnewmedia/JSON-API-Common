<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Factory;

use Enm\JsonApi\Factory\ResourceFactory;
use Enm\JsonApi\Model\Resource\ResourceInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ResourceFactoryTest extends TestCase
{
    public function testResourceFactory()
    {
        $factory = new ResourceFactory();
        $resource = $factory->create('test', 'test-1');
        self::assertInstanceOf(ResourceInterface::class, $resource);
        self::assertEquals('test', $resource->getType());
        self::assertEquals('test-1', $resource->getId());
    }
}
