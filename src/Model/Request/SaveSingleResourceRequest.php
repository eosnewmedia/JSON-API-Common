<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Request;

use Enm\JsonApi\Exception\BadRequestException;
use Enm\JsonApi\Model\Document\DocumentInterface;
use Psr\Http\Message\UriInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class SaveSingleResourceRequest extends Request
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

        $document = $this->requestBody();
        if (!$document) {
            throw new BadRequestException('A relationship modification requires a request body!');
        }

        if ($document->shouldBeHandledAsCollection()) {
            throw new BadRequestException('Bulk request are not supported yet.');
        }
        if ($document->data()->isEmpty()) {
            throw new BadRequestException('Missing a resource to save!');
        }
        if ($document->data()->first()->type() !== $this->type()) {
            throw new BadRequestException('Invalid resource type given!');
        }
        if ($this->id() && $document->data()->first()->id() !== $this->id()) {
            throw new BadRequestException('Invalid resource id given!');
        }
    }
}
