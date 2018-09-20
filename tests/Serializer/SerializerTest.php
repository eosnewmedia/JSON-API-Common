<?php
declare(strict_types=1);

namespace Enm\JsonApi\Server\Tests\Serializer;

use Enm\JsonApi\JsonApiInterface;
use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;
use Enm\JsonApi\Model\Document\Document;
use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Error\ErrorCollectionInterface;
use Enm\JsonApi\Model\Error\ErrorInterface;
use Enm\JsonApi\Model\Resource\JsonResource;
use Enm\JsonApi\Model\Resource\Link\LinkCollectionInterface;
use Enm\JsonApi\Model\Resource\Link\LinkInterface;
use Enm\JsonApi\Model\Resource\Relationship\Relationship;
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
    public function testSerializeErrorDocument(): void
    {
        $serializer = new Serializer();
        $document = new Document();
        /** @noinspection PhpParamsInspection */
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

        try {
            $serialized = $serializer->serializeDocument($document);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
            return;
        }

        self::assertArrayHasKey('errors', $serialized);
        self::assertEquals('1', $serialized['errors'][0]['code']);
        self::assertEquals('Test', $serialized['errors'][0]['title']);
        self::assertEquals('Test details', $serialized['errors'][0]['detail']);
        self::assertArrayNotHasKey('data', $serialized);
        self::assertArrayNotHasKey('links', $serialized);
        self::assertArrayNotHasKey('included', $serialized);
        self::assertArrayNotHasKey('meta', $serialized);
        self::assertEquals(JsonApiInterface::CURRENT_VERSION, $serialized['jsonapi']['version']);
    }

    public function testSerializeResourceDocument(): void
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
        try {
            $serialized = $serializer->serializeDocument($document);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
            return;
        }

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

    public function testSerializeResourceCollectionDocument(): void
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
        try {
            $serialized = $serializer->serializeDocument($document);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
            return;
        }

        self::assertArrayHasKey('data', $serialized);
        self::assertEquals('test', $serialized['data'][0]['type']);
        self::assertEquals('http://example.com', $serialized['links']['test']);
        self::assertEquals('test-1', $serialized['data'][0]['id']);
        self::assertEquals('test', $serialized['data'][1]['type']);
        self::assertEquals('test-2', $serialized['data'][1]['id']);
    }

    public function testEmptyResourceDocument(): void
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
        try {
            $serialized = $serializer->serializeDocument($document);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
            return;
        }

        self::assertNull($serialized['data']);
    }

    public function testEmptyResourceCollectionDocument(): void
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

        try {
            $serialized = $serializer->serializeDocument($document);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
            return;
        }

        self::assertCount(0, $serialized['data']);
    }

    public function testEmptyToManyRelationship(): void
    {
        $serializer = new Serializer();

        $jsonResource = new JsonResource('tests', 'ab631ed6-7ca0-49d0-97c7-ef8eb005ab44');
        $jsonResource->relationships()->set(new Relationship('test', []));

        /** @var DocumentInterface $document */
        $document = $this->createConfiguredMock(
            DocumentInterface::class,
            [
                'shouldBeHandledAsCollection' => false,
                'data' => $this->createConfiguredMock(
                    ResourceCollectionInterface::class,
                    [
                        'isEmpty' => false,
                        'first' => $jsonResource
                    ]
                ),
            ]
        );

        try {
            $serialized = $serializer->serializeDocument($document);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
            return;
        }
        self::assertCount(0, $serialized['data']['relationships']['test']['data']);
    }

    public function testEmptyToOneRelationship(): void
    {
        $serializer = new Serializer();

        $jsonResource = new JsonResource('tests', 'ab631ed6-7ca0-49d0-97c7-ef8eb005ab44');
        $jsonResource->relationships()->set(new Relationship('test'));

        /** @var DocumentInterface $document */
        $document = $this->createConfiguredMock(
            DocumentInterface::class,
            [
                'shouldBeHandledAsCollection' => false,
                'data' => $this->createConfiguredMock(
                    ResourceCollectionInterface::class,
                    [
                        'isEmpty' => false,
                        'first' => $jsonResource
                    ]
                ),
            ]
        );

        try {
            $serialized = $serializer->serializeDocument($document);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
            return;
        }
        self::assertNull($serialized['data']['relationships']['test']['data']);
    }

    public function testRelationshipWithRelatedMeta(): void
    {
        $serializer = new Serializer();

        $jsonResource = new JsonResource('tests', 'ab631ed6-7ca0-49d0-97c7-ef8eb005ab44');
        $related = new JsonResource('tests', '31ed6ab6-7ca0-49d0-97c7-ef85ab44eb00');
        $related->relatedMetaInformation()->set('test', 'test');

        $jsonResource->relationships()->set(new Relationship('test', $related));

        /** @var DocumentInterface $document */
        $document = $this->createConfiguredMock(
            DocumentInterface::class,
            [
                'shouldBeHandledAsCollection' => false,
                'data' => $this->createConfiguredMock(
                    ResourceCollectionInterface::class,
                    [
                        'isEmpty' => false,
                        'first' => $jsonResource
                    ]
                ),
            ]
        );

        try {
            $serialized = $serializer->serializeDocument($document);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
            return;
        }
        self::assertEquals('test', $serialized['data']['relationships']['test']['data']['meta']['test']);
    }

    /**
     * @param string $type
     * @param string $id
     *
     * @return ResourceInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function createResource(string $type, string $id)
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
     * @return KeyValueCollectionInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function createMetaCollection()
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
     * @return LinkInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function createLink($name)
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
     * @return RelationshipInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function createToOneRelationship(string $name)
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
     * @return RelationshipInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function createToManyRelationship(string $name)
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
