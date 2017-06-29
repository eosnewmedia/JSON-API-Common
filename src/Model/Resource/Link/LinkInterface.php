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
    public function name(): string;

    /**
     * @return string
     */
    public function href(): string;

    /**
     * @return KeyValueCollectionInterface
     */
    public function metaInformation(): KeyValueCollectionInterface;

    /**
     * Creates a new link containing all data from the current one.
     * If set, the new link will have the given name.
     *
     * @param string|null $name
     * @return LinkInterface
     */
    public function duplicate(string $name = null): LinkInterface;
}
