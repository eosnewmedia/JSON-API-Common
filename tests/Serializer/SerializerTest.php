<?php
declare(strict_types=1);

namespace Enm\JsonApi\Server\Tests\Serializer;

use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;
use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Document\ErrorDocument;
use Enm\JsonApi\Model\Error\ErrorInterface;
use Enm\JsonApi\Model\Resource\Link\LinkCollectionInterface;
use Enm\JsonApi\Model\Resource\Link\LinkInterface;
use Enm\JsonApi\Model\Resource\Relationship\RelationshipCollectionInterface;
use Enm\JsonApi\Model\Resource\Relationship\RelationshipInterface;
use Enm\JsonApi\Model\Resource\ResourceCollectionInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;
use Enm\JsonApi\Serializer\Serializer;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class SerializerTest extends TestCase
{
    public function testSerializeErrorDocument()
    {
        $serializer = new Serializer();
        $serialized = $serializer->serializeDocument(
            new ErrorDocument(
                [
                    $this->createConfiguredMock(
                        ErrorInterface::class,
                        [
                            'getCode' => '1',
                            'getTitle' => 'Test',
                            'getDetail' => 'Test details',
                        ]
                    ),
                ]
            )
        );

        self::assertArrayHasKey('errors', $serialized);
        self::assertEquals('1', $serialized['errors'][0]['code']);
        self::assertEquals('Test', $serialized['errors'][0]['title']);
        self::assertEquals('Test details', $serialized['errors'][0]['detail']);
        self::assertArrayNotHasKey('data', $serialized);
        self::assertArrayNotHasKey('links', $serialized);
        self::assertArrayNotHasKey('included', $serialized);
        self::assertArrayNotHasKey('meta', $serialized);
        self::assertEquals(Serializer::VERSION, $serialized['jsonapi']['version']);
    }

    public function testSerializeResourceDocument()
    {
        $serializer = new Serializer();
        /** @var DocumentInterface $document */
        $document = $this->createConfiguredMock(
            DocumentInterface::class,
            [
                'getType' => DocumentInterface::TYPE_RESOURCE,
                'data' =>
                    $this->createConfiguredMock(
                        ResourceCollectionInterface::class,
                        [
                            'first' => $this->createResource('test', 'test-1'),
                        ]
                    ),
                'links' => $this->createConfiguredMock(
                    LinkCollectionInterface::class,
                    [
                        'all' => [
                            $this->createLink('test'),
                        ],
                    ]
                ),
                'included' => $this->createConfiguredMock(
                    ResourceCollectionInterface::class,
                    [
                        'all' => [
                            $this->createResource('test', 'test-1'),
                        ],
                    ]
                ),
                'metaInformations' => $this->createMetaCollection(),
            ]
        );
        $serialized = $serializer->serializeDocument($document);

        self::assertArrayHasKey('data', $serialized);
        self::assertArrayHasKey('meta', $serialized);
        self::assertArrayHasKey('links', $serialized);
        self::assertArrayHasKey('included', $serialized);
        self::assertEquals('test', $serialized['data']['type']);
        self::assertEquals('test-1', $serialized['data']['id']);
        self::assertArrayHasKey(
            'relationA',
            $serialized['data']['relationships']
        );
        self::assertArrayHasKey(
            'relationB',
            $serialized['data']['relationships']
        );
    }

    public function testSerializeResourceCollectionDocument()
    {
        $serializer = new Serializer();
        /** @var DocumentInterface $document */
        $document = $this->createConfiguredMock(
            DocumentInterface::class,
            [
                'getType' => DocumentInterface::TYPE_RESOURCE_COLLECTION,
                'data' =>
                    $this->createConfiguredMock(
                        ResourceCollectionInterface::class,
                        [
                            'all' => [
                                $this->createResource('test', 'test-1'),
                                $this->createResource('test', 'test-2'),
                            ],
                        ]
                    ),
                'links' => $this->createConfiguredMock(
                    LinkCollectionInterface::class,
                    [
                        'all' => [
                            $this->createConfiguredMock(
                                LinkInterface::class,
                                [
                                    'getName' => 'test',
                                    'getHref' => 'http://example.com',
                                    'metaInformations' => $this->createConfiguredMock(
                                        KeyValueCollectionInterface::class,
                                        [
                                            'isEmpty' => true,
                                        ]
                                    ),
                                ]
                            ),
                        ],
                    ]
                ),
            ]
        );
        $serialized = $serializer->serializeDocument($document);

        self::assertArrayHasKey('data', $serialized);
        self::assertEquals('test', $serialized['data'][0]['type']);
        self::assertEquals('http://example.com', $serialized['links']['test']);
        self::assertEquals('test-1', $serialized['data'][0]['id']);
        self::assertEquals('test', $serialized['data'][1]['type']);
        self::assertEquals('test-2', $serialized['data'][1]['id']);
    }

    public function testSerializeRelationshipDocument()
    {
        $serializer = new Serializer();
        /** @var DocumentInterface $document */
        $document = $this->createConfiguredMock(
            DocumentInterface::class, [
                'getType' => DocumentInterface::TYPE_RELATIONSHIP,
                'data' => $this->createConfiguredMock(
                    ResourceCollectionInterface::class,
                    [
                        'first' => $this->createConfiguredMock(
                            ResourceInterface::class, [
                                'getType' => 'test',
                                'getId' => 'test-1',
                                'attributes' => $this->createConfiguredMock(
                                    KeyValueCollectionInterface::class,
                                    [
                                        'all' => ['test' => 'test'],
                                    ]
                                ),
                            ]
                        ),
                    ]
                ),
            ]
        );
        $serialized = $serializer->serializeDocument($document);

        self::assertArrayHasKey('data', $serialized);
        self::assertArrayNotHasKey('attributes', $serialized['data']);
        self::assertEquals('test', $serialized['data']['type']);
        self::assertEquals('test-1', $serialized['data']['id']);
    }

    public function testSerializeRelationshipCollectionDocument()
    {
        $serializer = new Serializer();
        /** @var DocumentInterface $document */
        $document = $this->createConfiguredMock(
            DocumentInterface::class, [
                'getType' => DocumentInterface::TYPE_RELATIONSHIP_COLLECTION,
                'data' => $this->createConfiguredMock(
                    ResourceCollectionInterface::class,
                    [
                        'all' => [
                            $this->createConfiguredMock(
                                ResourceInterface::class, [
                                    'getType' => 'test',
                                    'getId' => 'test-1',
                                    'attributes' => $this->createConfiguredMock(
                                        KeyValueCollectionInterface::class,
                                        [
                                            'all' => ['test' => 'test'],
                                        ]
                                    ),
                                ]
                            ),
                        ],
                    ]
                ),
            ]
        );
        $serialized = $serializer->serializeDocument($document);

        self::assertArrayHasKey('data', $serialized);
        self::assertArrayNotHasKey('attributes', $serialized['data'][0]);
        self::assertEquals('test', $serialized['data'][0]['type']);
        self::assertEquals('test-1', $serialized['data'][0]['id']);
    }

    public function testEmptyResourceDocument()
    {
        $serializer = new Serializer();
        /** @var DocumentInterface $document */
        $document = $this->createConfiguredMock(DocumentInterface::class,
            [
                'getType' => DocumentInterface::TYPE_RESOURCE,
                'data' => $this->createConfiguredMock(
                    ResourceCollectionInterface::class,
                    [
                        'isEmpty' => true,
                    ]
                ),
            ]);
        $serialized = $serializer->serializeDocument($document);

        self::assertNull($serialized['data']);
    }

    public function testEmptyResourceCollectionDocument()
    {
        $serializer = new Serializer();
        /** @var DocumentInterface $document */
        $document = $this->createConfiguredMock(DocumentInterface::class,
            [
                'getType' => DocumentInterface::TYPE_RESOURCE_COLLECTION,
                'data' => $this->createConfiguredMock(
                    ResourceCollectionInterface::class,
                    [
                        'isEmpty' => true,
                    ]
                ),
            ]);
        $serialized = $serializer->serializeDocument($document);

        self::assertCount(0, $serialized['data']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidDocumentEmptyData()
    {
        $serializer = new Serializer();
        /** @var DocumentInterface $document */
        $document = $this->createConfiguredMock(DocumentInterface::class,
            [
                'getType' => 'invalid',
                'data' => $this->createConfiguredMock(
                    ResourceCollectionInterface::class,
                    [
                        'isEmpty' => true,
                    ]
                ),
            ]);
        $serializer->serializeDocument($document);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidDocument()
    {
        $serializer = new Serializer();
        /** @var DocumentInterface $document */
        $document = $this->createConfiguredMock(
            DocumentInterface::class,
            ['getType' => 'invalid']
        );
        $serializer->serializeDocument($document);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidRelationship()
    {
        $serializer = new Serializer();
        /** @var DocumentInterface $document */
        $document = $this->createConfiguredMock(DocumentInterface::class,
            [
                'getType' => DocumentInterface::TYPE_RESOURCE,
                'data' => $this->createConfiguredMock(
                    ResourceCollectionInterface::class,
                    [
                        'isEmpty' => false,
                        'first' => $this->createConfiguredMock(
                            ResourceInterface::class,
                            [
                                'getType' => 'test',
                                'getId' => 'test-1',
                                'relationships' => $this->createConfiguredMock(
                                    RelationshipCollectionInterface::class,
                                    [
                                        'all' => [
                                            $this->createConfiguredMock(
                                                RelationshipInterface::class,
                                                [
                                                    'getType' => 'invalid',
                                                ]
                                            ),
                                        ],
                                    ]
                                ),
                            ]
                        ),
                    ]
                ),
            ]);
        $serializer->serializeDocument($document);
    }

    /**
     * @param string $type
     * @param string $id
     *
     * @return ResourceInterface
     */
    private function createResource(string $type, string $id): ResourceInterface
    {
        return $this->createConfiguredMock(
            ResourceInterface::class, [
                'getType' => $type,
                'getId' => $id,
                'attributes' => $this->createConfiguredMock(
                    KeyValueCollectionInterface::class,
                    [
                        'all' => ['test' => 'test'],
                    ]
                ),
                'relationships' => $this->createConfiguredMock(
                    RelationshipCollectionInterface::class,
                    [
                        'all' => [
                            $this->createToManyRelationship('relationA'),
                            $this->createToOneRelationship('relationB'),
                        ],
                    ]
                ),
                'links' => $this->createConfiguredMock(
                    LinkCollectionInterface::class,
                    [
                        'all' => [
                            $this->createLink('test'),
                        ],
                    ]
                ),
                'metaInformations' => $this->createMetaCollection(),
            ]
        );
    }

    /**
     * @return KeyValueCollectionInterface
     */
    private function createMetaCollection(): KeyValueCollectionInterface
    {
        return $this->createConfiguredMock(
            KeyValueCollectionInterface::class,
            [
                'all' => ['test' => 'test'],
            ]
        );
    }

    /**
     * @param $name
     *
     * @return LinkInterface
     */
    private function createLink($name): LinkInterface
    {
        return $this->createConfiguredMock(
            LinkInterface::class,
            [
                'getName' => $name,
                'getHref' => 'http://example.com',
                'metaInformations' => $this->createMetaCollection(),
            ]
        );
    }

    /**
     * @param string $name
     *
     * @return RelationshipInterface
     */
    private function createToOneRelationship(string $name): RelationshipInterface
    {
        return $this->createConfiguredMock(
            RelationshipInterface::class,
            [
                'getType' => RelationshipInterface::TYPE_ONE,
                'getName' => $name,
                'links' => $this->createConfiguredMock(
                    LinkCollectionInterface::class,
                    ['all' => [$this->createLink('relationship-link')]]
                ),
                'related' => $this->createConfiguredMock(
                    ResourceCollectionInterface::class,
                    [
                        'isEmpty' => false,
                        'all' => [
                            $this->createConfiguredMock(
                                ResourceInterface::class,
                                ['getType' => 'abc', 'getId' => '1']
                            ),
                        ],
                    ]
                ),
                'metaInformations' => $this->createMetaCollection(),
            ]
        );
    }

    /**
     * @param string $name
     *
     * @return RelationshipInterface
     */
    private function createToManyRelationship(string $name): RelationshipInterface
    {
        return $this->createConfiguredMock(
            RelationshipInterface::class,
            [
                'getType' => RelationshipInterface::TYPE_MANY,
                'getName' => $name,
                'links' => $this->createConfiguredMock(
                    LinkCollectionInterface::class,
                    ['all' => [$this->createLink('relationship-link')]]
                ),
                'related' => $this->createConfiguredMock(
                    ResourceCollectionInterface::class,
                    [
                        'isEmpty' => false,
                        'all' => [
                            $this->createConfiguredMock(
                                ResourceInterface::class,
                                ['getType' => 'abc', 'getId' => '1']
                            ),
                        ],
                    ]
                ),
                'metaInformations' => $this->createMetaCollection(),
            ]
        );
    }
}
