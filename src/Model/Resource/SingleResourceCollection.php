<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Resource;


/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class SingleResourceCollection extends ResourceCollection
{
    /**
     * @param ResourceInterface $resource
     *
     * @return ResourceCollectionInterface
     * @throws \LogicException
     */
    public function set(ResourceInterface $resource): ResourceCollectionInterface
    {
        if (!$this->isEmpty()) {
            throw new \LogicException('Tried to add a second resource to single resource collection...');
        }

        return parent::set($resource);
    }
}
