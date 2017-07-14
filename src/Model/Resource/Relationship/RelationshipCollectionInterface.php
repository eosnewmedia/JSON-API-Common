<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Resource\Relationship;

use Enm\JsonApi\Model\Common\CollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface RelationshipCollectionInterface extends CollectionInterface
{
    /**
     * @return RelationshipInterface[]
     */
    public function all(): array;

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * @param string $name
     *
     * @return RelationshipInterface
     */
    public function get(string $name): RelationshipInterface;

    /**
     * @param RelationshipInterface $relationship
     *
     * @return RelationshipCollectionInterface
     */
    public function set(RelationshipInterface $relationship): RelationshipCollectionInterface;

    /**
     * @param string $name
     * @return RelationshipCollectionInterface
     */
    public function remove(string $name): RelationshipCollectionInterface;

    /**
     * @param RelationshipInterface $relationship
     *
     * @return RelationshipCollectionInterface
     */
    public function removeElement(RelationshipInterface $relationship): RelationshipCollectionInterface;
}
