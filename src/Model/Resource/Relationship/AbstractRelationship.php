<?php
declare(strict_types = 1);

namespace Enm\JsonApi\Model\Resource\Relationship;

use Enm\JsonApi\Model\Common\KeyValueCollection;
use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;
use Enm\JsonApi\Model\Resource\Link\LinkCollection;
use Enm\JsonApi\Model\Resource\Link\LinkCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
abstract class AbstractRelationship implements RelationshipInterface
{
    /**
     * @var string
     */
    private $name;
    
    /**
     * @var LinkCollection
     */
    private $links;
    
    /**
     * @var KeyValueCollection
     */
    private $metaInformations;
    
    /**
     * @param string $name
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $name)
    {
        if ($name === '') {
            throw new \InvalidArgumentException('Invalid relationship');
        }
        $this->name = $name;
        
        $this->links            = new LinkCollection();
        $this->metaInformations = new KeyValueCollection();
    }
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @return LinkCollectionInterface
     */
    public function links(): LinkCollectionInterface
    {
        return $this->links;
    }
    
    /**
     * @return KeyValueCollectionInterface
     */
    public function metaInformations(): KeyValueCollectionInterface
    {
        return $this->metaInformations;
    }
}
