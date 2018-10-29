<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Resource\Link;

use Enm\JsonApi\Model\Common\CollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface LinkCollectionInterface extends CollectionInterface
{
    /**
     * @return LinkInterface[]
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
     * @return LinkInterface
     */
    public function get(string $name): LinkInterface;

    /**
     * @param LinkInterface $link
     *
     * @return LinkCollectionInterface
     */
    public function set(LinkInterface $link): LinkCollectionInterface;

    /**
     * @param LinkInterface $link
     * @param bool $replaceExistingValues
     * @return LinkCollectionInterface
     */
    public function merge(LinkInterface $link, bool $replaceExistingValues = false): LinkCollectionInterface;

    /**
     * @param string $name
     * @return LinkCollectionInterface
     */
    public function remove(string $name): LinkCollectionInterface;

    /**
     * @param LinkInterface $link
     * @return LinkCollectionInterface
     */
    public function removeElement(LinkInterface $link): LinkCollectionInterface;

    /**
     * @param string $name
     * @param string $href
     * @return LinkCollectionInterface
     */
    public function createLink(string $name, string $href): LinkCollectionInterface;
}
