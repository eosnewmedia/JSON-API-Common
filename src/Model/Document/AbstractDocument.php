<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Document;

use Enm\JsonApi\Model\Common\KeyValueCollection;
use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;
use Enm\JsonApi\Model\Error\ErrorCollection;
use Enm\JsonApi\Model\Error\ErrorCollectionInterface;
use Enm\JsonApi\Model\Resource\Link\LinkCollection;
use Enm\JsonApi\Model\Resource\Link\LinkCollectionInterface;
use Enm\JsonApi\Model\Resource\ResourceCollection;
use Enm\JsonApi\Model\Resource\ResourceCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
abstract class AbstractDocument implements DocumentInterface
{
    /**
     * @var ResourceCollectionInterface
     */
    private $data;

    /**
     * @var LinkCollection
     */
    private $links;

    /**
     * @var ResourceCollection
     */
    private $included;

    /**
     * @var KeyValueCollection
     */
    private $metaInformations;

    /**
     * @var ErrorCollection
     */
    private $errors;

    /**
     * @param ResourceCollectionInterface $resourceCollection
     */
    public function __construct(ResourceCollectionInterface $resourceCollection)
    {
        $this->data = $resourceCollection;
        $this->links = new LinkCollection();
        $this->included = new ResourceCollection();
        $this->metaInformations = new KeyValueCollection();
        $this->errors = new ErrorCollection();
    }

    /**
     * @return ResourceCollectionInterface
     */
    public function data(): ResourceCollectionInterface
    {
        return $this->data;
    }

    /**
     * @return LinkCollectionInterface
     */
    public function links(): LinkCollectionInterface
    {
        return $this->links;
    }

    /**
     * @return ResourceCollectionInterface
     */
    public function included(): ResourceCollectionInterface
    {
        return $this->included;
    }

    /**
     * @return KeyValueCollectionInterface
     */
    public function metaInformations(): KeyValueCollectionInterface
    {
        return $this->metaInformations;
    }

    /**
     * @return ErrorCollectionInterface
     */
    public function errors(): ErrorCollectionInterface
    {
        return $this->errors;
    }
}
