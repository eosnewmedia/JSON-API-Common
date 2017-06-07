<?php
declare(strict_types=1);

namespace Enm\JsonApi\Exception;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class UnsupportedTypeException extends Exception
{
    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        parent::__construct('Resource type "' . $type . '" not found');
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 404;
    }
}
