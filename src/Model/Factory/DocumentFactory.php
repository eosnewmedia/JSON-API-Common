<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Factory;

use Enm\JsonApi\JsonApiInterface;
use Enm\JsonApi\Model\Document\Document;
use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Resource\ResourceCollectionInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class DocumentFactory implements DocumentFactoryInterface
{
    /**
     * Create a document object
     * "data" can be null or a resource object for a "single resource document"
     * OR
     * an empty array, a ResourceCollection or an array of resource objects for a "multi resource document"
     *
     * @param ResourceInterface|ResourceInterface[]|ResourceCollectionInterface|array|null $data
     * @param string $version
     * @return DocumentInterface
     * @throws \InvalidArgumentException
     */
    public function create($data = null, string $version = JsonApiInterface::CURRENT_VERSION): DocumentInterface
    {
        return new Document($data, $version);
    }
}
