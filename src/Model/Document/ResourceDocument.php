<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Document;

use Enm\JsonApi\Model\Resource\ImmutableResourceCollection;
use Enm\JsonApi\Model\Resource\ResourceInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ResourceDocument extends AbstractDocument
{
    /**
     * @param ResourceInterface|null $data
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(ResourceInterface $data = null)
    {
        if ($data instanceof ResourceInterface) {
            parent::__construct(new ImmutableResourceCollection([$data]));
        } else {
            parent::__construct(new ImmutableResourceCollection());
        }
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE_RESOURCE;
    }
}
