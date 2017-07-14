<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests\Model\Request;

use Enm\JsonApi\Model\Document\Document;
use Enm\JsonApi\Model\Request\RelationshipModificationRequest;
use Enm\JsonApi\Model\Resource\JsonResource;
use PHPUnit\Framework\TestCase;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class RelationshipModificationRequestTest extends TestCase
{
    public function testRequest()
    {
        $document = new Document(new JsonResource('tests', 'test-1'));
        $request = new RelationshipModificationRequest('examples', 'example-1', $document);

        self::assertSame($document, $request->document());
    }

    /**
     * @expectedException \Enm\JsonApi\Exception\BadRequestException
     */
    public function testRequestInvalidType()
    {
        $document = new Document(new JsonResource('tests', 'test-1'));
        new RelationshipModificationRequest('', 'example-1', $document);
    }

    /**
     * @expectedException \Enm\JsonApi\Exception\BadRequestException
     */
    public function testRequestInvalidId()
    {
        $document = new Document(new JsonResource('tests', 'test-1'));
        new RelationshipModificationRequest('examples', '', $document);
    }

    /**
     * @expectedException \Enm\JsonApi\Exception\BadRequestException
     */
    public function testRequestInvalidResourceId()
    {
        $document = new Document(new JsonResource('test', ''));

        new RelationshipModificationRequest('examples', 'example-1', $document);
    }

    /**
     * @expectedException \Enm\JsonApi\Exception\BadRequestException
     */
    public function testRequestInvalidResourceType()
    {
        $document = new Document(
            [
                new JsonResource('tests', 'test-1'),
                new JsonResource('examples', 'example-2'),
            ]
        );

        new RelationshipModificationRequest('examples', 'example-1', $document);
    }
}
