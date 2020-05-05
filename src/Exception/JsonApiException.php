<?php
declare(strict_types=1);

namespace Enm\JsonApi\Exception;

use Enm\JsonApi\Model\Error\Error;
use Enm\JsonApi\Model\Error\ErrorCollection;
use Enm\JsonApi\Model\Error\ErrorCollectionInterface;
use Throwable;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class JsonApiException extends \Exception
{
    /** @var ErrorCollectionInterface */
    protected $errorCollection;

    public function __construct(
        $message = "",
        $code = 0,
        Throwable $previous = null,
        ErrorCollectionInterface $errors = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->errorCollection = $errors ?? new ErrorCollection();
        $this->errorCollection->add(
            Error::createFrom($this)
        );
    }

    public function getHttpStatus(): int
    {
        return 500;
    }

    public function errors(): ErrorCollectionInterface
    {
        return $this->errorCollection;
    }
}