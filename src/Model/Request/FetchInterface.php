<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Request;

use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface FetchInterface
{
    /**
     * @return string
     */
    public function type(): string;

    /**
     * Returns the url parameter "include" as one dimensional associative array
     *
     * For example:
     *          "?include=test1,test2,test2.relation,test2.relation.subRelation"
     *      looks like:
     *          [
     *              "test1" => [],
     *              "test2" => [
     *                  "relation",
     *                  "relation.subRelation"
     *              ]
     *          ]
     * @return array
     */
    public function includes(): array;

    /**
     * Returns the url parameter "filter" as simple collection
     *
     * @return KeyValueCollectionInterface
     */
    public function filter(): KeyValueCollectionInterface;

    /**
     * Returns the url parameter "page" as simple collection
     *
     * @return KeyValueCollectionInterface
     */
    public function pagination(): KeyValueCollectionInterface;

    /**
     * Add an include to the fetch request
     *
     * @param string $relationship
     * @return FetchInterface
     */
    public function include (string $relationship): FetchInterface;
}
