<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Resource\Link;

use Enm\JsonApi\Model\Common\KeyValueCollection;
use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class Link implements LinkInterface
{
    private $name;
    private $href;
    private $metaInformations;

    /**
     * @param string $name
     * @param string $href
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $name, string $href)
    {
        if ($name === '') {
            throw new \InvalidArgumentException('Invalid link name');
        }
        if (filter_var($href, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException('Invalid link target');
        }

        $this->name = $name;
        $this->href = $href;
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
     * @return string
     */
    public function getHref(): string
    {
        return $this->href;
    }

    /**
     * @return KeyValueCollectionInterface
     */
    public function metaInformations(): KeyValueCollectionInterface
    {
        return $this->metaInformations;
    }

    /**
     * Creates a new link containing all data from the current one.
     * If set, the new link will have the given name.
     *
     * @param string|null $name
     * @return LinkInterface
     * @throws \InvalidArgumentException
     */
    public function duplicate(string $name = null): LinkInterface
    {
        $link = new self($name ?? $this->getName(), $this->getHref());
        $link->metaInformations()->mergeCollection($this->metaInformations());

        return $link;
    }
}
