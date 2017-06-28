<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Serializer;

use Enm\JsonApi\Factory\DocumentFactory;
use Enm\JsonApi\Factory\ResourceFactory;
use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Resource\Relationship\RelationshipInterface;
use Enm\JsonApi\Serializer\Deserializer;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class DeserializerTest extends TestCase
{
    public function testDeserializeResourceDocument()
    {
        $document = $this->createDeserializer()->deserialize(
            DocumentInterface::TYPE_RESOURCE,
            [
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

        self::assertEquals('test', $document->data()->first()->getType());
        self::assertEquals(
            RelationshipInterface::TYPE_ONE,
            $document->data()->first()->relationships()->get('parent')->getType()
        );
        self::assertTrue($document->data()->first()->relationships()->get('parent')->links()->has('self'));
        self::assertTrue($document->data()->first()->relationships()->get('empty')->metaInformations()->has('empty'));
        self::assertEquals(
            RelationshipInterface::TYPE_MANY,
            $document->data()->first()->relationships()->get('children')->getType()
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidLink()
    {
        $this->createDeserializer()->deserialize(
            DocumentInterface::TYPE_RESOURCE,
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
        $this->createDeserializer()->deserialize(
            DocumentInterface::TYPE_RESOURCE,
            [
                'data' => [
                    'type' => 'test',
                ]
            ]
        );
    }

    public function testErrorDocument()
    {
        $document = $this->createDeserializer()->deserialize(
            DocumentInterface::TYPE_ERROR,
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
        self::assertEquals('Test', $document->errors()->all()[0]->getTitle());
        self::assertEquals('value', $document->errors()->all()[0]->metaInformations()->getRequired('key'));
    }

    public function testResourceCollectionDocument()
    {
        $document = $this->createDeserializer()->deserialize(
            DocumentInterface::TYPE_RESOURCE_COLLECTION,
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
                        'id' => 'test-2'
                    ]
                ]
            ]
        );

        self::assertEquals(1, $document->data()->count());
        self::assertEquals(1, $document->links()->count());
        self::assertEquals(1, $document->metaInformations()->count());
        self::assertEquals(1, $document->included()->count());
    }

    /**
     * @return Deserializer
     */
    protected function createDeserializer(): Deserializer
    {
        return new Deserializer(new DocumentFactory(), new ResourceFactory());
    }
}
