<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Factory;

use Enm\JsonApi\Model\Resource\Relationship\Relationship;
use Enm\JsonApi\Model\Resource\Relationship\RelationshipInterface;
use Enm\JsonApi\Model\Resource\ResourceCollectionInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class RelationshipFactory implements RelationshipFactoryInterface
{
    /**
     * @param string $name
     * @param array|ResourceCollectionInterface|ResourceInterface|ResourceInterface[]|null $related
     * @return RelationshipInterface
     * @throws \InvalidArgumentException
     */
    public function create(string $name, $related = null): RelationshipInterface
    {
        return new Relationship($name, $related);
    }
}
