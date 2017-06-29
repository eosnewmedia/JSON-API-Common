<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Factory;

use Enm\JsonApi\Model\Document\Document;
use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Document\JsonApi\JsonApiInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class DocumentFactory implements DocumentFactoryInterface
{
    use ResourceFactoryAwareTrait;

    /**
     * @param mixed $data
     * @param string $version
     * @return DocumentInterface
     * @throws \InvalidArgumentException
     */
    public function create($data = null, string $version = JsonApiInterface::CURRENT_VERSION): DocumentInterface
    {
        $document = new Document($data, $version);
        $document->data()->setResourceFactory($this->resourceFactory());
        $document->included()->setResourceFactory($this->resourceFactory());

        return $document;
    }
}
