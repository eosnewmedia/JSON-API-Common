<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Request;

use Enm\JsonApi\Exception\BadRequestException;
use Enm\JsonApi\Exception\JsonApiException;
use Enm\JsonApi\JsonApiInterface;
use Enm\JsonApi\Model\Common\KeyValueCollection;
use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class JsonApiRequest implements JsonApiRequestInterface
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $id;

    /**
     * @var KeyValueCollection
     */
    private $headers;

    /**
     * @param string $type
     * @param string $id
     * @throws JsonApiException
     */
    public function __construct(string $type, string $id = '')
    {
        if ($type === '') {
            throw new BadRequestException('No resource type requested!');
        }
        $this->type = $type;
        $this->id = $id;

        $this->headers = new KeyValueCollection(
            [
                'Content-Type' => JsonApiInterface::CONTENT_TYPE,
                'Accept' => JsonApiInterface::CONTENT_TYPE
            ]
        );
    }


    /**
     * Contains the requested resource type
     *
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Indicates if the request contains an id
     *
     * @return bool
     */
    public function containsId(): bool
    {
        return $this->id !== '';
    }

    /**
     * Contains the requested id or a created uuid if no id was requested
     *
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * Contains all request headers
     *
     * @return KeyValueCollectionInterface
     */
    public function headers(): KeyValueCollectionInterface
    {
        return $this->headers;
    }
}
