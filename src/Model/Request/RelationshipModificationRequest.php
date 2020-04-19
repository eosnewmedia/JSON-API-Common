<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Request;

use Enm\JsonApi\Exception\BadRequestException;
use Enm\JsonApi\Model\Document\DocumentInterface;
use Psr\Http\Message\UriInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 * @deprecated will be removed in 4.0
 */
class RelationshipModificationRequest extends Request
{
    /**
     * @param string $method
     * @param UriInterface $uri
     * @param DocumentInterface|null $requestBody
     * @param null|string $apiPrefix
     * @throws BadRequestException
     */
    public function __construct(
        string $method,
        UriInterface $uri,
        ?DocumentInterface $requestBody = null,
        ?string $apiPrefix = null
    ) {
        parent::__construct($method, $uri, $requestBody, $apiPrefix);

        if (!\in_array($this->method(), ['POST', 'PATCH'], true)) {
            throw new BadRequestException('Invalid http method.');
        }

        if ($this->type() === '' || (string)$this->id() === '') {
            throw new BadRequestException('A relationship must belong to a resource!');
        }

        $document = $this->requestBody();
        if (!$document) {
            throw new BadRequestException('A relationship modification requires a request body!');
        }

        $relatedType = false;
        foreach ($document->data()->all() as $resource) {
            if (!$relatedType) {
                $relatedType = $resource->type();
            }

            if ($resource->type() !== $relatedType) {
                throw new BadRequestException('Invalid resource type given!');
            }

            if ((string)$resource->id() === '') {
                throw new BadRequestException('Invalid resource id given!');
            }
        }
    }
}
