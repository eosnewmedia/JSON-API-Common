<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Resource;

use Enm\JsonApi\Exception\ResourceNotFoundException;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ImmutableResourceCollection implements ResourceCollectionInterface
{
    /**
     * @var ResourceCollection
     */
    private $collection;

    /**
     * @param ResourceInterface[] $data
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $data = [])
    {
        $this->collection = new ResourceCollection($data);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->collection->isEmpty();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->collection->count();
    }

    /**
     * @return ResourceInterface[]
     */
    public function all(): array
    {
        return $this->collection->all();
    }

    /**
     * @param string $type
     * @param string $id
     *
     * @return bool
     */
    public function has(string $type, string $id): bool
    {
        return $this->collection->has($type, $id);
    }

    /**
     * @param string $type
     * @param string $id
     *
     * @return ResourceInterface
     * @throws ResourceNotFoundException
     */
    public function get(string $type, string $id): ResourceInterface
    {
        return $this->collection->get($type, $id);
    }

    /**
     * @param string $type
     * @return ResourceInterface
     * @throws \LogicException
     */
    public function first(string $type = null): ResourceInterface
    {
        return $this->collection->first($type);
    }

    /**
     * @param ResourceInterface $resource
     *
     * @return ResourceCollectionInterface
     * @throws \LogicException
     */
    public function set(ResourceInterface $resource): ResourceCollectionInterface
    {
        throw new \LogicException('Tried to change an immutable collection...');
    }

    /**
     * @param string $type
     * @param string $id
     * @return ResourceCollectionInterface
     * @throws \LogicException
     */
    public function remove(string $type, string $id): ResourceCollectionInterface
    {
        throw new \LogicException('Tried to change an immutable collection...');
    }

    /**
     * @param ResourceInterface $resource
     *
     * @return ResourceCollectionInterface
     * @throws \LogicException
     */
    public function removeElement(ResourceInterface $resource): ResourceCollectionInterface
    {
        throw new \LogicException('Tried to change an immutable collection...');
    }
}
