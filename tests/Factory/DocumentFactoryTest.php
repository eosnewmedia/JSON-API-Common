<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Factory;

use Enm\JsonApi\Model\Factory\DocumentFactory;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class DocumentFactoryTest extends TestCase
{
    public function testCreateDocument()
    {
        $factory = new DocumentFactory();
        $document = $factory->create();

        self::assertEmpty($document->data()->all());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateInvalid()
    {
        $factory = new DocumentFactory();
        $factory->create('invalid');
    }
}
