<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Resource\Relationship;

use Enm\JsonApi\Model\Common\KeyValueCollection;
use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;
use Enm\JsonApi\Model\Factory\ResourceFactoryAwareTrait;
use Enm\JsonApi\Model\Resource\Link\LinkCollection;
use Enm\JsonApi\Model\Resource\Link\LinkCollectionInterface;
use Enm\JsonApi\Model\Resource\ResourceCollection;
use Enm\JsonApi\Model\Resource\ResourceCollectionInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;
use Enm\JsonApi\Model\Resource\SingleResourceCollection;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class Relationship implements RelationshipInterface
{
    use ResourceFactoryAwareTrait;

    /**
     * @var string
     */
    private $name;

    /**
     * @var ResourceCollectionInterface
     */
    private $related;

    /**
     * @var bool
     */
    private $handleAsCollection = true;

    /**
     * @var LinkCollection
     */
    private $links;

    /**
     * @var KeyValueCollection
     */
    private $metaInformation;

    /**
     * @param string $name
     * @param ResourceInterface|ResourceInterface[]|ResourceCollectionInterface|null $related
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $name, $related = null)
    {
        if ($name === '') {
            throw new \InvalidArgumentException('Invalid relationship');
        }
        $this->name = $name;

        if (null === $related || $related instanceof ResourceInterface) {
            $this->related = new SingleResourceCollection($related !== null ? [$related] : []);
            $this->handleAsCollection = false;
        } elseif ($related instanceof ResourceCollectionInterface) {
            $this->related = $related;
        } elseif (is_array($related)) {
            $this->related = new ResourceCollection($related);
        } else {
            throw new \InvalidArgumentException('Invalid relationship!');
        }

        $this->links = new LinkCollection();
        $this->metaInformation = new KeyValueCollection();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function shouldBeHandledAsCollection(): bool
    {
        return $this->handleAsCollection;
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
    public function metaInformation(): KeyValueCollectionInterface
    {
        return $this->metaInformation;
    }

    /**
     * @return ResourceCollectionInterface
     */
    public function related(): ResourceCollectionInterface
    {
        $this->related->setResourceFactory($this->resourceFactory());

        return $this->related;
    }

    /**
     * @param null|string $name
     * @return RelationshipInterface
     * @throws \InvalidArgumentException
     */
    public function duplicate(string $name = null): RelationshipInterface
    {
        if ($this->shouldBeHandledAsCollection()) {
            $related = [];
            foreach ($this->related()->all() as $resource) {
                $related[] = $resource->duplicate();
            }
        } else {
            $related = !$this->related()->isEmpty() ? $this->related()->first()->duplicate() : null;
        }

        $relationship = new self($name ?? $this->name(), $related);

        $relationship->metaInformation()->mergeCollection($this->metaInformation());
        foreach ($this->links()->all() as $link) {
            $relationship->links()->set($link->duplicate());
        }

        return $relationship;
    }
}
