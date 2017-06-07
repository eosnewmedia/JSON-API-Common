<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Error;

use Enm\JsonApi\Exception\Exception;
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
        if ($exception instanceof Exception) {
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
            $error->metaInformations()->set('file', $exception->getFile());
            $error->metaInformations()->set('line', $exception->getLine());
        }

        return $error;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDetail(): string
    {
        return $this->detail;
    }

    /**
     * @return KeyValueCollectionInterface
     */
    public function metaInformations(): KeyValueCollectionInterface
    {
        return $this->metaCollection;
    }
}
