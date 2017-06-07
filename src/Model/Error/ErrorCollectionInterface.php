<?php
declare(strict_types = 1);

namespace Enm\JsonApi\Model\Error;

use Enm\JsonApi\Model\Common\CollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface ErrorCollectionInterface extends CollectionInterface
{
    /**
     * @return ErrorInterface[]
     */
    public function all(): array;
    
    /**
     * @param ErrorInterface $error
     *
     * @return ErrorCollectionInterface
     */
    public function add(ErrorInterface $error): ErrorCollectionInterface;
}
