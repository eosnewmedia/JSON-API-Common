<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Factory;

use Enm\JsonApi\Model\Resource\Relationship\RelationshipInterface;
use Enm\JsonApi\Model\Resource\ResourceCollectionInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface RelationshipFactoryInterface
{
    /**
     * Create a relationship object
     * "data" can be null or a resource object for a "to one relationship"
     * OR
     * an empty array, a ResourceCollection or an array of resource objects for a "to many relationship"
     *
     * @param string $name
     * @param ResourceInterface|ResourceInterface[]|ResourceCollectionInterface|array|null $related
     * @return RelationshipInterface
     */
    public function create(string $name, $related = null): RelationshipInterface;
}
