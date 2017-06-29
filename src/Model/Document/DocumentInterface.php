<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Document;

use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;
use Enm\JsonApi\Model\Common\OneOrManyInterface;
use Enm\JsonApi\Model\Document\JsonApi\JsonApiInterface;
use Enm\JsonApi\Model\Error\ErrorCollectionInterface;
use Enm\JsonApi\Model\Resource\Link\LinkCollectionInterface;
use Enm\JsonApi\Model\Resource\ResourceCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface DocumentInterface extends OneOrManyInterface
{
    const HTTP_OK = 200;

    const HTTP_ACCEPTED = 202;

    const HTTP_NO_CONTENT = 204;

    /**
     * @return LinkCollectionInterface
     */
    public function links(): LinkCollectionInterface;

    /**
     * @return ResourceCollectionInterface
     */
    public function data(): ResourceCollectionInterface;

    /**
     * @return ResourceCollectionInterface
     */
    public function included(): ResourceCollectionInterface;

    /**
     * @return KeyValueCollectionInterface
     */
    public function metaInformation(): KeyValueCollectionInterface;

    /**
     * @return ErrorCollectionInterface
     */
    public function errors(): ErrorCollectionInterface;

    /**
     * @return JsonApiInterface
     */
    public function jsonApi(): JsonApiInterface;

    /**
     * @return int
     */
    public function httpStatus(): int;

    /**
     * @param int $statusCode
     * @return DocumentInterface
     */
    public function withHttpStatus(int $statusCode): DocumentInterface;
}
