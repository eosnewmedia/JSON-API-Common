<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Request;

use Enm\JsonApi\Exception\JsonApiException;
use Enm\JsonApi\Model\Common\KeyValueCollection;
use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class FetchRequest extends JsonApiRequest implements FetchRequestInterface
{
    /**
     * @var array
     */
    private $includes = [];

    /**
     * @var array
     */
    private $fields = [];

    /**
     * @var KeyValueCollectionInterface
     */
    private $sorting;

    /**
     * @var KeyValueCollectionInterface
     */
    private $pagination;

    /**
     * @var KeyValueCollectionInterface
     */
    private $filter;

    /**
     * @param string $type
     * @param string $id
     * @throws JsonApiException
     */
    public function __construct(string $type, string $id = '')
    {
        parent::__construct($type, $id);

        $this->sorting = new KeyValueCollection();
        $this->pagination = new KeyValueCollection();
        $this->filter = new KeyValueCollection();
    }

    /**
     * Contains the "include" parameter exploded by ",".
     *
     * @return array
     */
    public function includes(): array
    {
        return $this->includes;
    }

    /**
     * Includes a relationship on this request
     *
     * @param string $relationship
     * @return FetchRequestInterface
     */
    public function include (string $relationship): FetchRequestInterface
    {
        $this->includes[] = $relationship;

        return $this;
    }

    /**
     * Contains the "fields" parameter.
     * The resource type is always the key which contains an array of requested field names
     *
     * @return array
     */
    public function fields(): array
    {
        return $this->fields;
    }

    /**
     * @param string $type
     * @param string $name
     * @return FetchRequestInterface
     */
    public function field(string $type, string $name): FetchRequestInterface
    {
        $this->fields[$type][] = $name;

        return $this;
    }

    /**
     * Contains the "sort" parameter.
     * The field name is always the key while the value always have to be ResourceRequestInterface::ORDER_ASC or ResourceRequestInterface::ORDER_DESC
     *
     * @return KeyValueCollectionInterface
     */
    public function sorting(): KeyValueCollectionInterface
    {
        return $this->sorting;
    }

    /**
     * Contains the "page" parameter
     *
     * @return KeyValueCollectionInterface
     */
    public function pagination(): KeyValueCollectionInterface
    {
        return $this->pagination;
    }

    /**
     * Contains the "filter" parameter
     *
     * @return KeyValueCollectionInterface
     */
    public function filter(): KeyValueCollectionInterface
    {
        return $this->filter;
    }

    /**
     * Add filter parameter
     *
     * @param string $key
     * @param mixed $value
     * @return FetchRequestInterface
     */
    public function addFilter(string $key, $value): FetchRequestInterface
    {
        $this->filter->set($key, $value);

        return $this;
    }
}
