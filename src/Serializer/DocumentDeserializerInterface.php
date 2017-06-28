<?php
declare(strict_types=1);

namespace Enm\JsonApi\Serializer;

use Enm\JsonApi\Model\Document\DocumentInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface DocumentDeserializerInterface
{
    /**
     * @param string $type
     * @param array $documentData
     * @return DocumentInterface
     */
    public function deserialize(string $type, array $documentData): DocumentInterface;
}
