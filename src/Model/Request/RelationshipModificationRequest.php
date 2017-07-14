<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Request;

use Enm\JsonApi\Exception\BadRequestException;
use Enm\JsonApi\Exception\JsonApiException;
use Enm\JsonApi\Model\Document\DocumentInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class RelationshipModificationRequest extends JsonApiRequest implements SaveRequestInterface
{
    /**
     * @var DocumentInterface
     */
    private $document;

    /**
     * @param string $type
     * @param string $id
     * @param DocumentInterface $document
     * @throws JsonApiException
     */
    public function __construct(string $type, string $id, DocumentInterface $document)
    {
        if ($type === '' || $id === '') {
            throw new BadRequestException('A relationship must belong to a resource!');
        }

        parent::__construct($type, $id);

        $relatedType = false;
        foreach ($document->data()->all() as $resource) {
            if (!$relatedType) {
                $relatedType = $resource->type();
            }
            
            if ($resource->type() !== $relatedType) {
                throw new BadRequestException('Invalid resource type given!');
            }

            if ($resource->id() === '') {
                throw new BadRequestException('Invalid resource id given!');
            }
        }

        $this->document = $document;
    }

    /**
     * Contains the request document which contains the resource to save
     *
     * @return DocumentInterface
     */
    public function document(): DocumentInterface
    {
        return $this->document;
    }
}
