<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Factory;

use Enm\JsonApi\Model\Resource\JsonResource;
use Enm\JsonApi\Model\Resource\ResourceInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ResourceFactory implements ResourceFactoryInterface
{
    /**
     * @param string $type
     * @param string $id
     * @return ResourceInterface
     * @throws \InvalidArgumentException
     */
    public function create(string $type, string $id): ResourceInterface
    {
        return new JsonResource($type, $id);
    }
}
