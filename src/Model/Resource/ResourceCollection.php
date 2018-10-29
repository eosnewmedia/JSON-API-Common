<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Resource;

use Enm\JsonApi\Exception\ResourceNotFoundException;
use Enm\JsonApi\Model\Common\AbstractCollection;
use Enm\JsonApi\Model\Resource\Link\LinkInterface;
use Enm\JsonApi\Model\Resource\Relationship\RelationshipInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ResourceCollection extends AbstractCollection implements ResourceCollectionInterface
{
    /**
     * @param ResourceInterface[] $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct();
        foreach ($data as $resource) {
            $this->set($resource);
        }
    }

    /**
     * @return ResourceInterface[]
     */
    public function all(): array
    {
        return array_values(parent::all());
    }

    /**
     * @param string $type
     * @param string $id
     *
     * @return bool
     */
    public function has(string $type, string $id): bool
    {
        return array_key_exists($this->buildArrayKey($type, $id), $this->collection);
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
        if (!$this->has($type, $id)) {
            throw new ResourceNotFoundException($type, $id);
        }

        return $this->collection[$this->buildArrayKey($type, $id)];
    }

    /**
     * @param string $type
     * @return ResourceInterface
     * @throws \LogicException
     */
    public function first(string $type = null): ResourceInterface
    {
        if ($this->isEmpty()) {
            throw new \LogicException('Collection does not contain any resources!');
        }

        foreach ($this->all() as $resource) {
            if ($type === null || $resource->type() === $type) {
                return $resource;
            }
        }

        throw new \LogicException('Collection does not contain any resources of type ' . $type . '!');
    }

    /**
     * @param ResourceInterface $resource
     *
     * @return ResourceCollectionInterface
     */
    public function set(ResourceInterface $resource): ResourceCollectionInterface
    {
        $this->collection[$this->buildArrayKey($resource->type(), $resource->id())] = $resource;

        return $this;
    }

    /**
     * @param ResourceInterface $resource
     * @param bool $replaceExistingValues
     * @return ResourceCollectionInterface
     */
    public function merge(ResourceInterface $resource, bool $replaceExistingValues = false): ResourceCollectionInterface
    {
        try {
            $existing = $this->get($resource->type(), $resource->id());
        } catch (\Exception $e) {
            $this->set($resource);
            return $this;
        }

        $existing->metaInformation()->merge($resource->metaInformation()->all(), $replaceExistingValues);
        $existing->attributes()->merge($resource->attributes()->all(), $replaceExistingValues);

        /** @var LinkInterface $link */
        foreach ($resource->links() as $link) {
            $existing->links()->merge($link, $replaceExistingValues);
        }

        /** @var RelationshipInterface $relationship */
        foreach ($resource->relationships() as $relationship) {
            $existing->relationships()->merge($relationship, $replaceExistingValues);
        }

        return $this;
    }

    /**
     * @param string $type
     * @param string $id
     * @return ResourceCollectionInterface
     */
    public function remove(string $type, string $id): ResourceCollectionInterface
    {
        if ($this->has($type, $id)) {
            unset($this->collection[$this->buildArrayKey($type, $id)]);
        }

        return $this;
    }

    /**
     * @param ResourceInterface $resource
     *
     * @return ResourceCollectionInterface
     */
    public function removeElement(ResourceInterface $resource): ResourceCollectionInterface
    {
        $this->remove($resource->type(), $resource->id());

        return $this;
    }

    /**
     * @param string $type
     * @param string $id
     * @return string
     */
    private function buildArrayKey(string $type, string $id): string
    {
        return $type . '::' . $id;
    }
}
