<?php
declare(strict_types = 1);

namespace Enm\JsonApi\Model\Document;

use Enm\JsonApi\Model\Resource\ImmutableResourceCollection;
use Enm\JsonApi\Model\Resource\ResourceCollectionInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class RelationshipDocument extends AbstractDocument
{
    /**
     * @var ImmutableResourceCollection
     */
    private $data;
    
    /**
     * @param ResourceInterface|null $data
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(ResourceInterface $data = null)
    {
        parent::__construct();
        
        if ($data instanceof ResourceInterface) {
            $this->data = new ImmutableResourceCollection([$data]);
        } else {
            $this->data = new ImmutableResourceCollection();
        }
    }
    
    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE_RELATIONSHIP;
    }
    
    /**
     * @return ResourceCollectionInterface
     */
    public function data(): ResourceCollectionInterface
    {
        return $this->data;
    }
}
