<?php
declare(strict_types = 1);

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
    public function getStatus(): int;
    
    /**
     * @return string
     */
    public function getCode(): string;
    
    /**
     * @return string
     */
    public function getTitle(): string;
    
    /**
     * @return string
     */
    public function getDetail(): string;
    
    /**
     * @return KeyValueCollectionInterface
     */
    public function metaInformations(): KeyValueCollectionInterface;
}
