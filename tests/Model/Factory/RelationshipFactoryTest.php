<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Factory;

use Enm\JsonApi\Model\Factory\RelationshipFactory;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class RelationshipFactoryTest extends TestCase
{
    public function testCreate()
    {
        $factory = new RelationshipFactory();

        self::assertFalse($factory->create('relationship')->shouldBeHandledAsCollection());
        self::assertTrue($factory->create('relationship', [])->shouldBeHandledAsCollection());
    }
}
