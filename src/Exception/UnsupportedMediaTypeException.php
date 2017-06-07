<?php
declare(strict_types=1);

namespace Enm\JsonApi\Exception;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class UnsupportedMediaTypeException extends Exception
{
    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 415;
    }
}
