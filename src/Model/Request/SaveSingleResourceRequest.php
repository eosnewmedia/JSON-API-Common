<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Request;

use Enm\JsonApi\Exception\BadRequestException;
use Enm\JsonApi\Exception\JsonApiException;
use Enm\JsonApi\Model\Document\DocumentInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class SaveSingleResourceRequest extends JsonApiRequest implements SaveRequestInterface
{
    /**
     * @var DocumentInterface
     */
    private $document;

    /**
     * @param DocumentInterface $document
     * @param string $id
     * @throws JsonApiException
     */
    public function __construct(DocumentInterface $document, string $id = '')
    {
        if ($document->shouldBeHandledAsCollection()) {
            throw new BadRequestException('Bulk request are not supported yet.');
        }
        if ($document->data()->isEmpty()) {
            throw new BadRequestException('Missing a resource to save!');
        }

        if ($id !== '' && $document->data()->first()->id() !== $id) {
            throw new BadRequestException('Invalid id given!');
        }

        parent::__construct($document->data()->first()->type(), $id);
        $this->document = $document;
    }

    /**
     * Contains the document which contains the resource to save
     *
     * @return DocumentInterface
     */
    public function document(): DocumentInterface
    {
        return $this->document;
    }
}
