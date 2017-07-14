<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Serializer;

use Enm\JsonApi\Serializer\Deserializer;
use Enm\JsonApi\Tests\DummyJsonApi;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class DeserializerTest extends TestCase
{
    public function testDeserializeResourceDocument()
    {
        $documentDeserializer = $this->createDeserializer();

        $document = $documentDeserializer->deserializeDocument(
            [
                'jsonapi' => [
                    'version' => '1.0',
                    'meta' => ['test' => 'test']
                ],
                'data' => [
                    'type' => 'test',
                    'id' => 'test-2',
                    'attributes' => [
                        'key' => 'value'
                    ],
                    'links' => [
                        'self' => 'http://example.com',
                        'test' => ['href' => 'http://example.com/test', 'meta' => ['a' => 'b']],
                    ],
                    'relationships' => [
                        'parent' => [
                            'data' => [
                                'type' => 'test',
                                'id' => 'test-1'
                            ],
                            'links' => [
                                'self' => 'http://example.com/test/test-2/parent',
                            ]
                        ],
                        'children' => [
                            'data' => [
                                [
                                    'type' => 'test',
                                    'id' => 'test-3'
                                ]
                            ]
                        ],
                        'empty' => [
                            'meta' => [
                                'empty' => 'empty'
                            ]
                        ]
                    ],
                    'meta' => [
                        'metaKey' => 'metaValue'
                    ]
                ]
            ]
        );

        self::assertEquals('test', $document->data()->first()->type());
        self::assertFalse($document->data()->first()->relationships()->get('parent')->shouldBeHandledAsCollection());
        self::assertTrue($document->data()->first()->relationships()->get('parent')->links()->has('self'));
        self::assertTrue($document->data()->first()->relationships()->get('empty')->metaInformation()->has('empty'));
        self::assertTrue($document->data()->first()->relationships()->get('children')->shouldBeHandledAsCollection());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidLink()
    {
        $this->createDeserializer()->deserializeDocument(
            [
                'data' => [
                    'type' => 'test',
                    'id' => 'test-2',
                    'links' => [
                        'test' => [],
                    ],
                ]
            ]
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidResource()
    {
        $this->createDeserializer()->deserializeDocument(
            [
                'data' => [
                    'type' => 'test',
                ]
            ]
        );
    }

    public function testErrorDocument()
    {
        $document = $this->createDeserializer()->deserializeDocument(
            [
                'errors' => [
                    [
                        'title' => 'Test',
                        'meta' => [
                            'key' => 'value'
                        ]
                    ]
                ]
            ]
        );

        self::assertFalse($document->errors()->isEmpty());
        self::assertEquals('Test', $document->errors()->all()[0]->title());
        self::assertEquals('value', $document->errors()->all()[0]->metaInformation()->getRequired('key'));
    }

    public function testResourceCollectionDocument()
    {
        $document = $this->createDeserializer()->deserializeDocument(
            [
                'data' => [
                    [
                        'type' => 'test',
                        'id' => 'test-1'
                    ]
                ],
                'meta' => [
                    'key' => 'value'
                ],
                'links' => [
                    'self' => 'http://example.com/test'
                ],
                'included' => [
                    [
                        'type' => 'test',
                        'id' => 'test-2',
                        'relationships' => [
                            'related' => [
                                'data' => [
                                    'type' => 'example',
                                    'id' => 'example-1'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );

        self::assertEquals(1, $document->data()->count());
        self::assertEquals(1, $document->links()->count());
        self::assertEquals(1, $document->metaInformation()->count());
        self::assertEquals(1, $document->included()->count());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testMissingJsonApi()
    {
        $documentDeserializer = new Deserializer();
        $documentDeserializer->deserializeDocument(
            [
                'data' => [
                    'type' => 'test',
                    'id' => 'test-2',
                    'attributes' => [
                        'key' => 'value'
                    ],
                ]
            ]
        );
    }

    /**
     * @return Deserializer
     */
    protected function createDeserializer(): Deserializer
    {
        $documentDeserializer = new Deserializer();
        $documentDeserializer->setJsonApi(new DummyJsonApi());

        return $documentDeserializer;
    }
}
