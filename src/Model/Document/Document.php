<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Document;

use Enm\JsonApi\Model\Common\KeyValueCollection;
use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;
use Enm\JsonApi\Model\Document\JsonApi\JsonApi;
use Enm\JsonApi\Model\Document\JsonApi\JsonApiInterface;
use Enm\JsonApi\Model\Error\ErrorCollection;
use Enm\JsonApi\Model\Error\ErrorCollectionInterface;
use Enm\JsonApi\Model\Resource\Link\LinkCollection;
use Enm\JsonApi\Model\Resource\Link\LinkCollectionInterface;
use Enm\JsonApi\Model\Resource\ResourceCollection;
use Enm\JsonApi\Model\Resource\ResourceCollectionInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;
use Enm\JsonApi\Model\Resource\SingleResourceCollection;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class Document implements DocumentInterface
{
    /**
     * @var bool
     */
    private $handleAsCollection = true;

    /**
     * @var ResourceCollectionInterface
     */
    private $data;

    /**
     * @var LinkCollection
     */
    private $links;

    /**
     * @var ResourceCollection
     */
    private $included;

    /**
     * @var KeyValueCollection
     */
    private $metaInformation;

    /**
     * @var ErrorCollection
     */
    private $errors;

    /**
     * @var JsonApi
     */
    private $jsonApi;

    /**
     * @var int
     */
    private $httpStatus = self::HTTP_OK;

    /**
     * @param ResourceCollectionInterface|ResourceInterface|ResourceInterface[]|null $data If data is not an array, "shouldBeHandledAsCollection" will return false
     * @param string $version
     * @throws \InvalidArgumentException
     */
    public function __construct($data = null, string $version = JsonApiInterface::CURRENT_VERSION)
    {
        if (null === $data || $data instanceof ResourceInterface) {
            $this->data = new SingleResourceCollection($data !== null ? [$data] : []);
            $this->handleAsCollection = false;
        } elseif ($data instanceof ResourceCollectionInterface) {
            $this->data = $data;
        } elseif (is_array($data)) {
            $this->data = new ResourceCollection($data);
        } else {
            throw new \InvalidArgumentException('Invalid data given!');
        }

        $this->links = new LinkCollection();
        $this->included = new ResourceCollection();
        $this->metaInformation = new KeyValueCollection();
        $this->errors = new ErrorCollection();
        $this->jsonApi = new JsonApi($version);
    }

    /**
     * @return bool
     */
    public function shouldBeHandledAsCollection(): bool
    {
        return $this->handleAsCollection;
    }

    /**
     * @return ResourceCollectionInterface
     */
    public function data(): ResourceCollectionInterface
    {
        return $this->data;
    }

    /**
     * @return LinkCollectionInterface
     */
    public function links(): LinkCollectionInterface
    {
        return $this->links;
    }

    /**
     * @return ResourceCollectionInterface
     */
    public function included(): ResourceCollectionInterface
    {
        return $this->included;
    }

    /**
     * @return KeyValueCollectionInterface
     */
    public function metaInformation(): KeyValueCollectionInterface
    {
        return $this->metaInformation;
    }

    /**
     * @return ErrorCollectionInterface
     */
    public function errors(): ErrorCollectionInterface
    {
        return $this->errors;
    }

    /**
     * @return JsonApiInterface
     */
    public function jsonApi(): JsonApiInterface
    {
        return $this->jsonApi;
    }

    /**
     * @return int
     */
    public function httpStatus(): int
    {
        return $this->httpStatus;
    }

    /**
     * @param int $statusCode
     * @return DocumentInterface
     */
    public function withHttpStatus(int $statusCode): DocumentInterface
    {
        $this->httpStatus = $statusCode;

        return $this;
    }
}
