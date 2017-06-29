<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Resource;

use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;
use Enm\JsonApi\Model\Resource\Link\LinkCollectionInterface;
use Enm\JsonApi\Model\Resource\Relationship\RelationshipCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface ResourceInterface
{
    /**
     * @return string
     */
    public function type(): string;

    /**
     * @return string
     */
    public function id(): string;

    /**
     * @return KeyValueCollectionInterface
     */
    public function attributes(): KeyValueCollectionInterface;

    /**
     * @return RelationshipCollectionInterface
     */
    public function relationships(): RelationshipCollectionInterface;

    /**
     * @return LinkCollectionInterface
     */
    public function links(): LinkCollectionInterface;

    /**
     * @return KeyValueCollectionInterface
     */
    public function metaInformation(): KeyValueCollectionInterface;

    /**
     * Creates a new resource containing all data from the current one.
     * If set, the new resource will have the given id.
     *
     * @param string $id
     * @return ResourceInterface
     */
    public function duplicate(string $id = null): ResourceInterface;
}
