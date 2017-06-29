<?php
declare(strict_types=1);


namespace Enm\JsonApi;

use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;


/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface JsonApiInterface
{
    /**
     * @param string $type
     * @param string $id
     * @return ResourceInterface
     */
    public function resource(string $type, string $id): ResourceInterface;

    /**
     * @param ResourceInterface|null $resource
     * @return DocumentInterface
     */
    public function singleResourceDocument(ResourceInterface $resource = null): DocumentInterface;

    /**
     * @param array $resource
     * @return DocumentInterface
     */
    public function multiResourceDocument(array $resource = []): DocumentInterface;

    /**
     * @param DocumentInterface $document
     * @return array
     */
    public function serializeDocument(DocumentInterface $document): array;

    /**
     * @param array $document
     * @return DocumentInterface
     */
    public function deserializeDocument(array $document): DocumentInterface;
}
