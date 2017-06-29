<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Error;

use Enm\JsonApi\Exception\JsonApiException;
use Enm\JsonApi\Model\Common\KeyValueCollection;
use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class Error implements ErrorInterface
{
    /**
     * @var int
     */
    private $status;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $detail;

    /**
     * @var string
     */
    private $code;

    /**
     * @var KeyValueCollection
     */
    private $metaCollection;

    /**
     * @param int $status
     * @param string $title
     * @param string $detail
     * @param string $code
     */
    public function __construct(int $status, string $title, string $detail = '', string $code = '')
    {
        $this->status = $status;
        $this->title = $title;
        $this->detail = $detail;
        $this->code = $code;

        $this->metaCollection = new KeyValueCollection();
    }

    /**
     * @param \Exception $exception
     * @param bool $debug
     *
     * @return ErrorInterface
     */
    public static function createFromException(\Exception $exception, $debug = false): ErrorInterface
    {
        $status = 500;
        if ($exception instanceof JsonApiException) {
            $status = $exception->getHttpStatus();
        }

        $code = '';
        if ($exception->getCode() !== 0) {
            $code = (string)$exception->getCode();
        }

        $error = new self(
            $status,
            $exception->getMessage(),
            ($debug ? $exception->getTraceAsString() : ''),
            $code
        );

        if ($debug) {
            $error->metaInformation()->set('file', $exception->getFile());
            $error->metaInformation()->set('line', $exception->getLine());
        }

        return $error;
    }

    /**
     * @return int
     */
    public function status(): int
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function code(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function detail(): string
    {
        return $this->detail;
    }

    /**
     * @return KeyValueCollectionInterface
     */
    public function metaInformation(): KeyValueCollectionInterface
    {
        return $this->metaCollection;
    }
}
