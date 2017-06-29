<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Resource;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ImmutableResourceCollection extends ResourceCollection
{
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
