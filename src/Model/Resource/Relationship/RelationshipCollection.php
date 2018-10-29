<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Resource\Relationship;

use Enm\JsonApi\Model\Common\AbstractCollection;
use Enm\JsonApi\Model\Resource\Link\LinkInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class RelationshipCollection extends AbstractCollection implements RelationshipCollectionInterface
{
    /**
     * @param RelationshipInterface[] $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct();
        foreach ($data as $relationship) {
            $this->set($relationship);
        }
    }

    /**
     * @return RelationshipInterface[]
     */
    public function all(): array
    {
        return array_values(parent::all());
    }


    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->collection);
    }

    /**
     * @param string $name
     *
     * @return RelationshipInterface
     * @throws \InvalidArgumentException
     */
    public function get(string $name): RelationshipInterface
    {
        if (!$this->has($name)) {
            throw new \InvalidArgumentException('Relationship ' . $name . ' not available');
        }

        return $this->collection[$name];
    }

    /**
     * @param RelationshipInterface $relationship
     *
     * @return RelationshipCollectionInterface
     */
    public function set(RelationshipInterface $relationship): RelationshipCollectionInterface
    {
        $this->collection[$relationship->name()] = $relationship;

        return $this;
    }

    /**
     * @param RelationshipInterface $relationship
     * @param bool $replaceExistingValues
     * @return RelationshipCollectionInterface
     */
    public function merge(
        RelationshipInterface $relationship,
        bool $replaceExistingValues = false
    ): RelationshipCollectionInterface {
        try {
            $existing = $this->get($relationship->name());
        } catch (\Exception $e) {
            $this->set($relationship);
            return $this;
        }

        $existing->metaInformation()->merge($relationship->metaInformation()->all(), $replaceExistingValues);

        /** @var LinkInterface $link */
        foreach ($relationship->links() as $link) {
            $existing->links()->merge($link, $replaceExistingValues);
        }

        /** @var ResourceInterface $related */
        foreach ($relationship->related() as $related) {
            $existing->related()->merge($related, $replaceExistingValues);
        }

        return $this;
    }

    /**
     * @param string $name
     * @return RelationshipCollectionInterface
     */
    public function remove(string $name): RelationshipCollectionInterface
    {
        if ($this->has($name)) {
            unset($this->collection[$name]);
        }

        return $this;
    }

    /**
     * @param RelationshipInterface $relationship
     *
     * @return RelationshipCollectionInterface
     */
    public function removeElement(RelationshipInterface $relationship): RelationshipCollectionInterface
    {
        $this->remove($relationship->name());

        return $this;
    }
}
