<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Factory;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */

interface RelationshipFactoryAwareInterface
{
    /**
     * @param RelationshipFactoryInterface $relationshipFactory
     * @return void
     */
    public function setRelationshipFactory(RelationshipFactoryInterface $relationshipFactory);
}
