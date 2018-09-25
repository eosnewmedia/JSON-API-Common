<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Request;

use Enm\JsonApi\Model\Document\Document;
use Enm\JsonApi\Model\Request\SaveSingleResourceRequest;
use Enm\JsonApi\Model\Resource\JsonResource;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class SaveSingleResourceRequestTest extends TestCase
{
    /**
     * @throws \Enm\JsonApi\Exception\BadRequestException
     */
    public function testSaveRequest(): void
    {
        // create
        $document = new Document(new JsonResource('test', 'test-1'));
        $request = new SaveSingleResourceRequest('POST', new Uri('/test'), $document);


        self::assertSame($document, $request->requestBody());

        // patch
        $document = new Document(new JsonResource('test', 'test-1'));
        $request = new SaveSingleResourceRequest('POST', new Uri('/test/test-1'), $document);

        self::assertSame($document, $request->requestBody());
    }

    /**
     * @expectedException \Enm\JsonApi\Exception\BadRequestException
     */
    public function testSaveRequestInvalidType(): void
    {
        $document = new Document(new JsonResource('tests', 'example-1'));
        new SaveSingleResourceRequest('POST', new Uri('/examples/example-1'), $document);
    }

    /**
     * @expectedException \Enm\JsonApi\Exception\BadRequestException
     */
    public function testSaveRequestInvalidId(): void
    {
        $document = new Document(new JsonResource('examples', 'test-1'));
        new SaveSingleResourceRequest('POST', new Uri('/examples/example-1'), $document);
    }

    /**
     * @expectedException \Enm\JsonApi\Exception\BadRequestException
     */
    public function testSaveRequestCollectionData(): void
    {
        new SaveSingleResourceRequest('POST', new Uri('/examples/example-1'), new Document([]));
    }

    /**
     * @expectedException \Enm\JsonApi\Exception\BadRequestException
     */
    public function testSaveRequestEmptyData(): void
    {
        new SaveSingleResourceRequest('POST', new Uri('/examples/example-1'));
    }
}
