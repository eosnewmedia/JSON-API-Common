<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests;

use Enm\JsonApi\Model\Factory\DocumentFactory;
use Enm\JsonApi\Model\Factory\ResourceFactory;
use Enm\JsonApi\Model\Resource\ResourceInterface;
use Enm\JsonApi\Serializer\Deserializer;
use Enm\JsonApi\Serializer\Serializer;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class JsonApiTest extends TestCase
{
    public function testResource()
    {
        $api = new DummyJsonApi();

        self::assertInstanceOf(ResourceInterface::class, $api->resource('test', 'test-1'));
    }

    public function testSingleResourceDocument()
    {
        $api = new DummyJsonApi();

        self::assertFalse($api->singleResourceDocument()->shouldBeHandledAsCollection());
    }

    public function testMultiResourceDocument()
    {
        $api = new DummyJsonApi();

        self::assertTrue($api->multiResourceDocument()->shouldBeHandledAsCollection());
    }

    public function testSerializeDocument()
    {
        $api = new DummyJsonApi();

        $data = $api->serializeDocument($api->singleResourceDocument());
        self::assertArrayHasKey('data', $data);
        self::assertNull($data['data']);
    }

    public function testDeserializeDocument()
    {
        $api = new DummyJsonApi();

        $document = $api->deserializeDocument(['data' => null]);
        self::assertEquals(0, $document->data()->count());
        self::assertFalse($document->shouldBeHandledAsCollection());
    }

    public function testSetDocumentFactory()
    {
        $api = new DummyJsonApi();
        $factory = new DocumentFactory();

        $reflection = new \ReflectionObject($api);
        $method = $reflection->getMethod('documentFactory');
        $method->setAccessible(true);

        self::assertNotSame(
            $factory,
            $method->invoke($api)
        );

        $api->setDocumentFactory($factory);

        self::assertSame(
            $factory,
            $method->invoke($api)
        );
    }

    public function testSetResourceFactory()
    {
        $api = new DummyJsonApi();
        $factory = new ResourceFactory();

        $reflection = new \ReflectionObject($api);
        $method = $reflection->getMethod('resourceFactory');
        $method->setAccessible(true);

        self::assertNotSame(
            $factory,
            $method->invoke($api)
        );

        $api->setResourceFactory($factory);

        self::assertSame(
            $factory,
            $method->invoke($api)
        );
    }

    public function testSetDocumentSerializer()
    {
        $api = new DummyJsonApi();
        $documentSerializer = new Serializer();

        $reflection = new \ReflectionObject($api);
        $method = $reflection->getMethod('documentSerializer');
        $method->setAccessible(true);

        self::assertNotSame(
            $documentSerializer,
            $method->invoke($api)
        );

        $api->setDocumentSerializer($documentSerializer);

        self::assertSame(
            $documentSerializer,
            $method->invoke($api)
        );
    }

    public function testSetDocumentDeserializer()
    {
        $api = new DummyJsonApi();
        $documentDeserializer = new Deserializer();

        $reflection = new \ReflectionObject($api);
        $method = $reflection->getMethod('documentDeserializer');
        $method->setAccessible(true);

        self::assertNotSame(
            $documentDeserializer,
            $method->invoke($api)
        );

        $api->setDocumentDeserializer($documentDeserializer);

        self::assertSame(
            $documentDeserializer,
            $method->invoke($api)
        );
    }
}
