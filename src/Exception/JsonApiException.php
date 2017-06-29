<?php
declare(strict_types=1);

namespace Enm\JsonApi\Exception;

use Enm\JsonApi\Model\Error\Error;
use Enm\JsonApi\Model\Error\ErrorInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class JsonApiException extends \Exception
{
    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 500;
    }

    /**
     * @return ErrorInterface
     */
    public function createError(): ErrorInterface
    {
        return Error::createFromException($this);
    }
}
