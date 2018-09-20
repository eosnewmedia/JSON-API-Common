<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests;

use Enm\JsonApi\JsonApiInterface;
use Enm\JsonApi\JsonApiTrait;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class JsonApiTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testResource(): void
    {
        /** @var JsonApiInterface $jsonApi */
        $jsonApi = $this->getObjectForTrait(JsonApiTrait::class);
        self::assertEquals('test-1', $jsonApi->resource('test', 'test-1')->id());
    }

    /**
     * @throws \ReflectionException
     */
    public function testSingleResourceDocument(): void
    {
        /** @var JsonApiInterface $jsonApi */
        $jsonApi = $this->getObjectForTrait(JsonApiTrait::class);

        self::assertFalse($jsonApi->singleResourceDocument()->shouldBeHandledAsCollection());
    }

    /**
     * @throws \ReflectionException
     */
    public function testMultiResourceDocument(): void
    {
        /** @var JsonApiInterface $jsonApi */
        $jsonApi = $this->getObjectForTrait(JsonApiTrait::class);

        self::assertTrue($jsonApi->multiResourceDocument()->shouldBeHandledAsCollection());
    }

    /**
     * @throws \ReflectionException
     */
    public function testToOneRelationship(): void
    {
        /** @var JsonApiInterface $jsonApi */
        $jsonApi = $this->getObjectForTrait(JsonApiTrait::class);

        self::assertFalse($jsonApi->toOneRelationship('test')->shouldBeHandledAsCollection());
    }

    /**
     * @throws \ReflectionException
     */
    public function testToManyRelationship(): void
    {
        /** @var JsonApiInterface $jsonApi */
        $jsonApi = $this->getObjectForTrait(JsonApiTrait::class);
        self::assertTrue($jsonApi->toManyRelationship('test')->shouldBeHandledAsCollection());
    }

    /**
     * @throws \ReflectionException
     */
    public function testUuid(): void
    {
        /** @var JsonApiInterface $jsonApi */
        $jsonApi = $this->getObjectForTrait(JsonApiTrait::class);
        self::assertStringMatchesFormat(
            '%x%x%x%x%x%x%x%x-%x%x%x%x-%x%x%x%x-%x%x%x%x-%x%x%x%x%x%x%x%x%x%x%x%x',
            $jsonApi->generateUuid()
        );
    }
}
