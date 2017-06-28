<?php
declare(strict_types=1);

namespace Enm\JsonApi\Factory;

use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Document\ErrorDocument;
use Enm\JsonApi\Model\Document\RelationshipCollectionDocument;
use Enm\JsonApi\Model\Document\RelationshipDocument;
use Enm\JsonApi\Model\Document\ResourceCollectionDocument;
use Enm\JsonApi\Model\Document\ResourceDocument;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class DocumentFactory implements DocumentFactoryInterface
{
    /**
     * @param string $type
     * @param mixed $data
     * @return DocumentInterface
     * @throws \InvalidArgumentException
     */
    public function create(string $type, $data = null): DocumentInterface
    {
        switch ($type) {
            case DocumentInterface::TYPE_RESOURCE:
                return new ResourceDocument($data);

            case DocumentInterface::TYPE_RELATIONSHIP:
                return new RelationshipDocument($data);

            case DocumentInterface::TYPE_RESOURCE_COLLECTION:
                return new ResourceCollectionDocument($this->getDataAsArray($data));

            case DocumentInterface::TYPE_RELATIONSHIP_COLLECTION:
                return new RelationshipCollectionDocument($this->getDataAsArray($data));

            case DocumentInterface::TYPE_ERROR:
                return new ErrorDocument($this->getDataAsArray($data));
        }

        throw new \InvalidArgumentException('Invalid document type!');
    }

    /**
     * @param mixed $data
     * @return array
     */
    protected function getDataAsArray($data): array
    {
        if ($data === null) {
            return [];
        }

        if (is_array($data)) {
            return $data;
        }

        return [$data];
    }
}
