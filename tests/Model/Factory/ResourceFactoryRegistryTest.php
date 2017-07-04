<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Factory;

use Enm\JsonApi\Model\Factory\ResourceFactory;
use Enm\JsonApi\Model\Factory\ResourceFactoryRegistry;
use Enm\JsonApi\Model\Resource\ResourceInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ResourceFactoryRegistryTest extends TestCase
{
    public function testRegistry()
    {
        $registry = new ResourceFactoryRegistry();

        $registry->setDefaultFactory(new ResourceFactory());
        $registry->addResourceFactory('test', new ResourceFactory());

        self::assertInstanceOf(ResourceInterface::class, $registry->create('test', '123'));
        self::assertInstanceOf(ResourceInterface::class, $registry->create('secondTest', '123'));
    }
}
