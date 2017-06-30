<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Factory;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
trait RelationshipFactoryAwareTrait
{
    /**
     * @var RelationshipFactoryInterface
     */
    private $relationshipFactory;

    /**
     * @param RelationshipFactoryInterface $relationshipFactory
     *
     * @return void
     */
    public function setRelationshipFactory(RelationshipFactoryInterface $relationshipFactory)
    {
        $this->relationshipFactory = $relationshipFactory;
    }

    /**
     * @return RelationshipFactoryInterface
     */
    protected function relationshipFactory(): RelationshipFactoryInterface
    {
        if (!$this->relationshipFactory instanceof RelationshipFactoryInterface) {
            $this->relationshipFactory = new RelationshipFactory();
        }

        return $this->relationshipFactory;
    }
}
