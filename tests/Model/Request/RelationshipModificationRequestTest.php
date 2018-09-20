<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Request;

use Enm\JsonApi\Model\Document\Document;
use Enm\JsonApi\Model\Request\RelationshipModificationRequest;
use Enm\JsonApi\Model\Resource\JsonResource;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class RelationshipModificationRequestTest extends TestCase
{
    /**
     * @throws \Enm\JsonApi\Exception\BadRequestException
     */
    public function testRequest(): void
    {
        $document = new Document(new JsonResource('tests', 'test-1'));
        $request = new RelationshipModificationRequest('POST', new Uri('/examples/example-1'), $document);

        self::assertSame($document, $request->requestBody());
    }

    /**
     * @expectedException \Enm\JsonApi\Exception\BadRequestException
     */
    public function testRequestInvalidType(): void
    {
        $document = new Document(new JsonResource('tests', 'test-1'));
        new RelationshipModificationRequest('POST', new Uri('/'), $document);
    }

    /**
     * @expectedException \Enm\JsonApi\Exception\BadRequestException
     */
    public function testRequestInvalidId(): void
    {
        $document = new Document(new JsonResource('tests', 'test-1'));
        new RelationshipModificationRequest('POST', new Uri('/tests'), $document);
    }

    /**
     * @expectedException \Enm\JsonApi\Exception\BadRequestException
     */
    public function testRequestInvalidResourceId(): void
    {
        $document = new Document(new JsonResource('test', ''));

        new RelationshipModificationRequest('POST', new Uri('/examples/example-1'), $document);
    }

    /**
     * @expectedException \Enm\JsonApi\Exception\BadRequestException
     */
    public function testRequestInvalidResourceType(): void
    {
        $document = new Document(
            [
                new JsonResource('tests', 'test-1'),
                new JsonResource('examples', 'example-2'),
            ]
        );

        new RelationshipModificationRequest('POST', new Uri('/examples/example-1'), $document);
    }
}
