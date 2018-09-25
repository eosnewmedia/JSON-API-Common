<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Response;

use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;
use Enm\JsonApi\Model\Document\DocumentInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface ResponseInterface
{
    /**
     * @return int
     */
    public function status(): int;

    /**
     * @return KeyValueCollectionInterface
     */
    public function headers(): KeyValueCollectionInterface;

    /**
     * @return DocumentInterface|null
     */
    public function document(): ?DocumentInterface;
}
