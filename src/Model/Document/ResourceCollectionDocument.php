<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Document;

use Enm\JsonApi\Model\Resource\ResourceCollection;
use Enm\JsonApi\Model\Resource\ResourceInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ResourceCollectionDocument extends AbstractDocument
{
    /**
     * @param ResourceInterface[] $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct(new ResourceCollection($data));
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE_RESOURCE_COLLECTION;
    }
}
