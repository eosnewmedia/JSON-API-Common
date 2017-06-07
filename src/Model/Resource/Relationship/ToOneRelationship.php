<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Resource\Relationship;

use Enm\JsonApi\Model\Resource\ImmutableResourceCollection;
use Enm\JsonApi\Model\Resource\ResourceCollectionInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ToOneRelationship extends AbstractRelationship
{
    /**
     * @var ImmutableResourceCollection
     */
    private $related;

    /**
     * @param string $name
     * @param ResourceInterface|null $resource
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $name, ResourceInterface $resource = null)
    {
        parent::__construct($name);
        if ($resource instanceof ResourceInterface) {
            $this->related = new ImmutableResourceCollection([$resource]);
        } else {
            $this->related = new ImmutableResourceCollection();
        }
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE_ONE;
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
        $relationship = new self(
            $name ?? $this->getName(),
            $this->related()->isEmpty() ? null : $this->related()->first()->duplicate()
        );

        $relationship->metaInformations()->mergeCollection($this->metaInformations());
        foreach ($this->links()->all() as $link) {
            $relationship->links()->set($link->duplicate());
        }

        return $relationship;
    }
}
