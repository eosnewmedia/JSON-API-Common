<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Resource\Link;

use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface LinkInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getHref(): string;

    /**
     * @return KeyValueCollectionInterface
     */
    public function metaInformations(): KeyValueCollectionInterface;

    /**
     * Creates a new link containing all data from the current one.
     * If set, the new link will have the given name.
     *
     * @param string|null $name
     * @return LinkInterface
     */
    public function duplicate(string $name = null): LinkInterface;
}
