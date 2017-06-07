<?php
declare(strict_types = 1);

namespace Enm\JsonApi\Model\Document;

use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;
use Enm\JsonApi\Model\Error\ErrorCollectionInterface;
use Enm\JsonApi\Model\Resource\Link\LinkCollectionInterface;
use Enm\JsonApi\Model\Resource\ResourceCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface DocumentInterface
{
    const TYPE_RESOURCE_COLLECTION = 'resource_collection';
    const TYPE_RESOURCE = 'resource';
    const TYPE_RELATIONSHIP_COLLECTION = 'relationship_collection';
    const TYPE_RELATIONSHIP = 'relationship';
    const TYPE_ERROR = 'error';
    
    /**
     * @return string
     */
    public function getType(): string;
    
    /**
     * @return LinkCollectionInterface
     */
    public function links(): LinkCollectionInterface;
    
    /**
     * @return ResourceCollectionInterface
     */
    public function data(): ResourceCollectionInterface;
    
    /**
     * @return ResourceCollectionInterface
     */
    public function included(): ResourceCollectionInterface;
    
    /**
     * @return KeyValueCollectionInterface
     */
    public function metaInformations(): KeyValueCollectionInterface;
    
    /**
     * @return ErrorCollectionInterface
     */
    public function errors(): ErrorCollectionInterface;
}
