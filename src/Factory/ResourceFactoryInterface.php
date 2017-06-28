<?php
declare(strict_types=1);

namespace Enm\JsonApi\Factory;

use Enm\JsonApi\Model\Resource\ResourceInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface ResourceFactoryInterface
{
    /**
     * @param string $type
     * @param string $id
     * @return ResourceInterface
     */
    public function create(string $type, string $id): ResourceInterface;
}
