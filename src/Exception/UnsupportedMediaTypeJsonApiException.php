<?php
declare(strict_types=1);

namespace Enm\JsonApi\Exception;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class UnsupportedMediaTypeJsonApiException extends JsonApiException
{
    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 415;
    }
}
