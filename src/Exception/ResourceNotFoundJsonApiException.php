<?php
declare(strict_types=1);

namespace Enm\JsonApi\Exception;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ResourceNotFoundJsonApiException extends JsonApiException
{
    /**
     * @param string $type
     * @param string $id
     */
    public function __construct(string $type, string $id)
    {
        parent::__construct('Resource "' . $id . '" of type "' . $type . '" not found!');
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 404;
    }
}
