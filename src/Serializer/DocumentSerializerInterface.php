<?php
declare(strict_types=1);

namespace Enm\JsonApi\Serializer;

use Enm\JsonApi\Model\Document\DocumentInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface DocumentSerializerInterface
{
    /**
     * @param DocumentInterface $document
     *
     * @return array
     */
    public function serializeDocument(DocumentInterface $document): array;
}
