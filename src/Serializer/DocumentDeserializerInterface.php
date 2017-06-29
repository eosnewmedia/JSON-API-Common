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
     * @param array $documentData
     * @return DocumentInterface
     */
    public function deserializeDocument(array $documentData): DocumentInterface;
}
