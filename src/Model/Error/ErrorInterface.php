<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Error;

use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface ErrorInterface
{
    /**
     * @return int
     */
    public function status(): int;

    /**
     * @return string
     */
    public function code(): string;

    /**
     * @return string
     */
    public function title(): string;

    /**
     * @return string
     */
    public function detail(): string;

    /**
     * @return KeyValueCollectionInterface
     */
    public function metaInformation(): KeyValueCollectionInterface;
}
