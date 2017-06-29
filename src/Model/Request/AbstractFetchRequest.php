<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Request;

use Enm\JsonApi\Model\Common\KeyValueCollection;
use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
abstract class AbstractFetchRequest implements FetchInterface
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $includes = [];

    /**
     * @var array
     */
    private $requestedRelationships = [];

    /**
     * @var KeyValueCollectionInterface
     */
    private $filter;

    /**
     * @var KeyValueCollectionInterface
     */
    private $pagination;

    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
        $this->filter = new KeyValueCollection();
        $this->pagination = new KeyValueCollection();
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function includes(): array
    {
        return $this->includes;
    }

    /**
     * @return KeyValueCollectionInterface
     */
    public function filter(): KeyValueCollectionInterface
    {
        return $this->filter;
    }

    /**
     * @return KeyValueCollectionInterface
     */
    public function pagination(): KeyValueCollectionInterface
    {
        return $this->pagination;
    }

    /**
     * @param string $relationship
     * @return FetchInterface
     */
    public function include (string $relationship): FetchInterface
    {
        if (strpos($relationship, '.') === false) {
            if (!array_key_exists($relationship, $this->includes)) {
                $this->includes[$relationship] = [];
                $this->requestedRelationships[] = $relationship;
            }

            return $this;
        }

        list($rootInclude, $subInclude) = explode('.', $relationship, 2);
        $this->includes[$rootInclude][] = $subInclude;

        return $this;
    }

    /**
     * @param string $relationship
     * @return array
     */
    protected function subIncludes(string $relationship): array
    {
        if (array_key_exists($relationship, $this->includes)) {
            return $this->includes[$relationship];
        }

        return [];
    }
}
