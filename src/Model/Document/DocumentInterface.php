<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Document;

use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;
use Enm\JsonApi\Model\Error\ErrorCollectionInterface;
use Enm\JsonApi\Model\Resource\Link\LinkCollectionInterface;
use Enm\JsonApi\Model\Resource\ResourceCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface DocumentInterface
{
    /**
     * Indicates if the contained data should be handled as object collection or single object
     *
     * @return bool
     */
    public function shouldBeHandledAsCollection(): bool;

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
}
