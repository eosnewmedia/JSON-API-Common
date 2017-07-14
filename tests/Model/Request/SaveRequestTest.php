<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Request;

use Enm\JsonApi\Model\Document\Document;
use Enm\JsonApi\Model\Request\SaveSingleResourceRequest;
use Enm\JsonApi\Model\Resource\JsonResource;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class SaveRequestTest extends TestCase
{
    public function testSaveRequest()
    {
        // create
        $document = new Document(new JsonResource('test', 'test-1'));
        $request = new SaveSingleResourceRequest($document);

        self::assertSame($document, $request->document());

        // patch
        $document = new Document(new JsonResource('test', 'test-1'));
        $request = new SaveSingleResourceRequest($document, 'test-1');

        self::assertSame($document, $request->document());
    }

    /**
     * @expectedException \Enm\JsonApi\Exception\BadRequestException
     */
    public function testSaveRequestInvalidId()
    {
        $document = new Document(new JsonResource('test', 'test-1'));
        new SaveSingleResourceRequest($document, 'test-2');
    }

    /**
     * @expectedException \Enm\JsonApi\Exception\BadRequestException
     */
    public function testSaveRequestCollectionData()
    {
        new SaveSingleResourceRequest(new Document([]));
    }

    /**
     * @expectedException \Enm\JsonApi\Exception\BadRequestException
     */
    public function testSaveRequestEmptyData()
    {
        new SaveSingleResourceRequest(new Document());
    }
}
