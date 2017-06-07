<?php
declare(strict_types=1);

namespace Enm\JsonApi\Exception;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class InvalidRequestException extends Exception
{
    /**
     * @param string $message
     */
    public function __construct(string $message = '')
    {
        if ($message === '') {
            $message = 'Invalid Request!';
        }
        parent::__construct($message);
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 400;
    }
}
