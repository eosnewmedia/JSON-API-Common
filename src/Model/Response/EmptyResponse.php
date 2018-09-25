<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Response;

use Enm\JsonApi\Model\Common\KeyValueCollection;
use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;
use Enm\JsonApi\Model\Document\DocumentInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class EmptyResponse extends AbstractResponse
{
    /**
     * @param KeyValueCollectionInterface|null $headers
     */
    public function __construct(?KeyValueCollectionInterface $headers = null)
    {
        parent::__construct(204, $headers ?? new KeyValueCollection());
    }

    /**
     * @return DocumentInterface|null
     */
    public function document(): ?DocumentInterface
    {
        return null;
    }
}
