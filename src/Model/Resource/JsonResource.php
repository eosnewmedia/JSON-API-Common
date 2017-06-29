<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Resource;

use Enm\JsonApi\Model\Common\KeyValueCollection;
use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;
use Enm\JsonApi\Model\Resource\Link\LinkCollection;
use Enm\JsonApi\Model\Resource\Link\LinkCollectionInterface;
use Enm\JsonApi\Model\Resource\Relationship\RelationshipCollection;
use Enm\JsonApi\Model\Resource\Relationship\RelationshipCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class JsonResource implements ResourceInterface
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $id;

    /**
     * @var KeyValueCollection
     */
    private $attributeCollection;

    /**
     * @var RelationshipCollection
     */
    private $relationshipCollection;

    /**
     * @var LinkCollection
     */
    private $linkCollection;

    /**
     * @var KeyValueCollection
     */
    private $metaCollection;

    /**
     * @param string $type
     * @param string $id
     * @param array $attributes
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $type, string $id, array $attributes = [])
    {
        if ($type === '' || $id === '') {
            throw new \InvalidArgumentException('Invalid resource identifier');
        }
        $this->type = $type;
        $this->id = $id;

        $this->attributeCollection = new KeyValueCollection($attributes);
        $this->relationshipCollection = new RelationshipCollection();
        $this->linkCollection = new LinkCollection();
        $this->metaCollection = new KeyValueCollection();
    }


    /**
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return KeyValueCollectionInterface
     */
    public function attributes(): KeyValueCollectionInterface
    {
        return $this->attributeCollection;
    }

    /**
     * @return RelationshipCollectionInterface
     */
    public function relationships(): RelationshipCollectionInterface
    {
        return $this->relationshipCollection;
    }

    /**
     * @return LinkCollectionInterface
     */
    public function links(): LinkCollectionInterface
    {
        return $this->linkCollection;
    }

    /**
     * @return KeyValueCollectionInterface
     */
    public function metaInformation(): KeyValueCollectionInterface
    {
        return $this->metaCollection;
    }

    /**
     * Creates a new resource containing all data from the current one.
     * If set, the new request will have the given id.
     *
     * @param string $id
     * @return ResourceInterface
     * @throws \InvalidArgumentException
     */
    public function duplicate(string $id = null): ResourceInterface
    {
        $resource = new self($this->type(), $id ?? $this->id(), $this->attributes()->all());

        $resource->metaInformation()->mergeCollection($this->metaInformation());

        foreach ($this->relationships()->all() as $relationship) {
            $resource->relationships()->set($relationship->duplicate());
        }

        foreach ($this->links()->all() as $link) {
            $resource->links()->set($link->duplicate());
        }

        return $resource;
    }
}
