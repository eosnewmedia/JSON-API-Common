<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Response;

use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
abstract class AbstractResponse implements ResponseInterface
{
    /**
     * @var int
     */
    private $status;

    /**
     * @var KeyValueCollectionInterface
     */
    private $headers;

    /**
     * @param int $status
     * @param KeyValueCollectionInterface $headers
     */
    public function __construct(int $status, KeyValueCollectionInterface $headers)
    {
        $this->status = $status;
        $this->headers = $headers;
        $this->headers->set('Content-Type', 'application/vnd.api+json');
    }

    /**
     * @return int
     */
    public function status(): int
    {
        return $this->status;
    }

    /**
     * @return KeyValueCollectionInterface
     */
    public function headers(): KeyValueCollectionInterface
    {
        return $this->headers;
    }
}
