<?php
declare(strict_types=1);

namespace Enm\JsonApi\Exception;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class NotAllowedException extends JsonApiException
{
    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 403;
    }
}
