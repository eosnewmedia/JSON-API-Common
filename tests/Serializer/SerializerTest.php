<?php
declare(strict_types=1);

namespace Enm\JsonApi\Server\Tests\Serializer;

use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;
use Enm\JsonApi\Model\Document\Document;
use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Error\ErrorCollectionInterface;
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
        $document = new Document();
        $document->errors()->add(
            $this->createConfiguredMock(
                ErrorInterface::class,
                [
                    'code' => '1',
                    'title' => 'Test',
                    'detail' => 'Test details',
                ]
            )
        );

        $serialized = $serializer->serializeDocument($document);

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
                'shouldBeHandledAsCollection' => false,
                'data' =>
                    $this->createConfiguredMock(
                        ResourceCollectionInterface::class,
                        [
                            'isEmpty' => false,
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
                'metaInformation' => $this->createMetaCollection(),
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
                'shouldBeHandledAsCollection' => true,
                'data' =>
                    $this->createConfiguredMock(
                        ResourceCollectionInterface::class,
                        [
                            'isEmpty' => false,
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
                                    'name' => 'test',
                                    'href' => 'http://example.com',
                                    'metaInformation' => $this->createConfiguredMock(
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

    public function testEmptyResourceDocument()
    {
        $serializer = new Serializer();
        /** @var DocumentInterface $document */
        $document = $this->createConfiguredMock(DocumentInterface::class,
            [
                'shouldBeHandledAsCollection' => false,
                'data' => $this->createConfiguredMock(
                    ResourceCollectionInterface::class,
                    [
                        'isEmpty' => true,
                    ]
                ),
                'errors' => $this->createConfiguredMock(
                    ErrorCollectionInterface::class,
                    [
                        'isEmpty' => true
                    ]
                ),
                'metaInformation' => $this->createConfiguredMock(
                    KeyValueCollectionInterface::class,
                    [
                        'isEmpty' => true
                    ]
                )
            ]
        );
        $serialized = $serializer->serializeDocument($document);

        self::assertNull($serialized['data']);
    }

    public function testEmptyResourceCollectionDocument()
    {
        $serializer = new Serializer();
        /** @var DocumentInterface $document */
        $document = $this->createConfiguredMock(DocumentInterface::class,
            [
                'shouldBeHandledAsCollection' => true,
                'data' => $this->createConfiguredMock(
                    ResourceCollectionInterface::class,
                    [
                        'isEmpty' => true,
                    ]
                ),
                'errors' => $this->createConfiguredMock(
                    ErrorCollectionInterface::class,
                    [
                        'isEmpty' => true
                    ]
                ),
                'metaInformation' => $this->createConfiguredMock(
                    KeyValueCollectionInterface::class,
                    [
                        'isEmpty' => true
                    ]
                )
            ]);
        $serialized = $serializer->serializeDocument($document);

        self::assertCount(0, $serialized['data']);
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
                'type' => $type,
                'id' => $id,
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
                'metaInformation' => $this->createMetaCollection(),
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
                'name' => $name,
                'href' => 'http://example.com',
                'metaInformation' => $this->createMetaCollection(),
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
                'shouldBeHandledAsCollection' => false,
                'name' => $name,
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
                                ['type' => 'abc', 'id' => '1']
                            ),
                        ],
                    ]
                ),
                'metaInformation' => $this->createMetaCollection(),
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
                'shouldBeHandledAsCollection' => true,
                'name' => $name,
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
                                ['type' => 'abc', 'id' => '1']
                            ),
                        ],
                    ]
                ),
                'metaInformation' => $this->createMetaCollection(),
            ]
        );
    }
}
