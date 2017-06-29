<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Resource\Link;

use Enm\JsonApi\Model\Common\AbstractCollection;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class LinkCollection extends AbstractCollection implements LinkCollectionInterface
{
    /**
     * @param LinkInterface[] $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct();
        foreach ($data as $link) {
            $this->set($link);
        }
    }

    /**
     * @return LinkInterface[]
     */
    public function all(): array
    {
        return array_values(parent::all());
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->collection);
    }

    /**
     * @param string $name
     *
     * @return LinkInterface
     * @throws \InvalidArgumentException
     */
    public function get(string $name): LinkInterface
    {
        if (!$this->has($name)) {
            throw new \InvalidArgumentException('Missing link ' . $name);
        }

        return $this->collection[$name];
    }

    /**
     * @param LinkInterface $link
     *
     * @return LinkCollectionInterface
     */
    public function set(LinkInterface $link): LinkCollectionInterface
    {
        $this->collection[$link->name()] = $link;

        return $this;
    }

    /**
     * @param string $name
     * @return LinkCollectionInterface
     */
    public function remove(string $name): LinkCollectionInterface
    {
        if ($this->has($name)) {
            unset($this->collection[$name]);
        }

        return $this;
    }

    /**
     * @param LinkInterface $link
     *
     * @return LinkCollectionInterface
     */
    public function removeElement(LinkInterface $link): LinkCollectionInterface
    {
        $this->remove($link->name());

        return $this;
    }

    /**
     * @param string $name
     * @param string $href
     * @return LinkCollectionInterface
     * @throws \InvalidArgumentException
     */
    public function createLink(string $name, string $href): LinkCollectionInterface
    {
        $this->set(new Link($name, $href));

        return $this;
    }
}
