<?php
declare(strict_types = 1);

namespace Enm\JsonApi\Model\Document;

use Enm\JsonApi\Model\Resource\ResourceCollection;
use Enm\JsonApi\Model\Resource\ResourceCollectionInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ResourceCollectionDocument extends AbstractDocument
{
    /**
     * @var ResourceCollection
     */
    private $data;
    
    /**
     * @param ResourceInterface[] $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct();
        $this->data = new ResourceCollection($data);
    }
    
    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE_RESOURCE_COLLECTION;
    }
    
    /**
     * @return ResourceCollectionInterface
     */
    public function data(): ResourceCollectionInterface
    {
        return $this->data;
    }
}
