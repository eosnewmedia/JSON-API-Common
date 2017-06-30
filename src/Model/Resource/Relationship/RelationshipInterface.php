<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Resource\Relationship;

use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;
use Enm\JsonApi\Model\Resource\Link\LinkCollectionInterface;
use Enm\JsonApi\Model\Resource\ResourceCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface RelationshipInterface
{
    /**
     * Indicates if the contained data should be handled as object collection or single object
     *
     * @return bool
     */
    public function shouldBeHandledAsCollection(): bool;

    /**
     * @return string
     */
    public function name(): string;

    /**
     * @return ResourceCollectionInterface
     */
    public function related(): ResourceCollectionInterface;

    /**
     * @return LinkCollectionInterface
     */
    public function links(): LinkCollectionInterface;

    /**
     * @return KeyValueCollectionInterface
     */
    public function metaInformation(): KeyValueCollectionInterface;

    /**
     * Creates a new relationship containing all data from the current one.
     * If set, the new relationship will have the given name.
     *
     * @param string|null $name
     * @return RelationshipInterface
     */
    public function duplicate(string $name = null): RelationshipInterface;
}
