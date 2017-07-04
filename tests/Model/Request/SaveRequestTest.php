<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Request;

use Enm\JsonApi\Model\Document\Document;
use Enm\JsonApi\Model\Request\SaveRequest;
use Enm\JsonApi\Model\Resource\JsonResource;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class SaveRequestTest extends TestCase
{
    public function testSaveRequest()
    {
        $document = new Document(new JsonResource('test', 'test-1'));
        $request = new SaveRequest($document);

        self::assertSame($document, $request->document());
    }

    /**
     * @expectedException \Enm\JsonApi\Exception\BadRequestException
     */
    public function testSaveRequestCollectionData()
    {
        new SaveRequest(new Document([]));
    }

    /**
     * @expectedException \Enm\JsonApi\Exception\BadRequestException
     */
    public function testSaveRequestEmptyData()
    {
        new SaveRequest(new Document());
    }
}
