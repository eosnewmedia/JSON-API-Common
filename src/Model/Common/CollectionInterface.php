<?php
declare(strict_types = 1);

namespace Enm\JsonApi\Model\Common;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface CollectionInterface extends \Countable
{
    /**
     * @return array
     */
    public function all(): array;

    /**
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * @return int
     */
    public function count(): int;
}
