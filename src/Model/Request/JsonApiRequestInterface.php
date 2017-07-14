<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Request;

use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface JsonApiRequestInterface
{
    /**
     * Contains the requested resource type
     *
     * @return string
     */
    public function type(): string;

    /**
     * Indicates if the request contains an id
     *
     * @return bool
     */
    public function containsId(): bool;

    /**
     * Contains the requested id if available
     *
     * @return string
     */
    public function id(): string;

    /**
     * Contains all request headers
     *
     * @return KeyValueCollectionInterface
     */
    public function headers(): KeyValueCollectionInterface;
}
