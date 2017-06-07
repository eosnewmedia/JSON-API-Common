<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Resource\Relationship;

use Enm\JsonApi\Model\Resource\ResourceCollection;
use Enm\JsonApi\Model\Resource\ResourceCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ToManyRelationship extends AbstractRelationship
{
    /**
     * @var ResourceCollection
     */
    private $related;

    /**
     * @param string $name
     * @param array $resources
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $name, array $resources = [])
    {
        parent::__construct($name);
        $this->related = new ResourceCollection();
        foreach ($resources as $resource) {
            $this->related()->set($resource);
        }
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE_MANY;
    }

    /**
     * @return ResourceCollectionInterface
     */
    public function related(): ResourceCollectionInterface
    {
        return $this->related;
    }

    /**
     * Creates a new relationship containing all data from the current one.
     * If set, the new relationship will have the given name.
     *
     * @param string|null $name
     * @return RelationshipInterface
     * @throws \InvalidArgumentException
     */
    public function duplicate(string $name = null): RelationshipInterface
    {
        $resources = [];
        foreach ($this->related()->all() as $resource) {
            $resources[] = $resource->duplicate();
        }

        $relationship = new self($name ?? $this->getName(), $resources);

        $relationship->metaInformations()->mergeCollection($this->metaInformations());
        foreach ($this->links()->all() as $link) {
            $relationship->links()->set($link->duplicate());
        }

        return $relationship;
    }
}
