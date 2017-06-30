<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Document\JsonApi;

use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface JsonApiInterface
{
    /**
     * @return string
     */
    public function getVersion(): string;

    /**
     * @return KeyValueCollectionInterface
     */
    public function metaInformation(): KeyValueCollectionInterface;
}
