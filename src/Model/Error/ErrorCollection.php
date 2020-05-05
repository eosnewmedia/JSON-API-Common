<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Error;

use Enm\JsonApi\Model\Common\AbstractCollection;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ErrorCollection extends AbstractCollection implements ErrorCollectionInterface
{
    /**
     * @param ErrorInterface $error
     *
     * @return ErrorCollectionInterface
     */
    public function add(ErrorInterface $error): ErrorCollectionInterface
    {
        $this->collection[] = $error;

        return $this;
    }

    public function first(): ?ErrorInterface
    {
        return reset($this->collection) ?: null;
    }
}