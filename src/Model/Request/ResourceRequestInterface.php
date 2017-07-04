<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Request;

use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface ResourceRequestInterface extends JsonApiRequestInterface
{
    const ORDER_ASC = 'asc';
    const ORDER_DESC = 'desc';

    /**
     * Contains the "include" parameter exploded by ",".
     *
     * @return array
     */
    public function includes(): array;

    /**
     * Includes a relationship on this request
     *
     * @param string $relationship
     * @return ResourceRequestInterface
     */
    public function include (string $relationship): ResourceRequestInterface;

    /**
     * Contains the "fields" parameter.
     * The resource type is always the key which contains an array of requested field names
     *
     * @return array
     */
    public function fields(): array;

    /**
     * @param string $type
     * @param string $name
     * @return ResourceRequestInterface
     */
    public function field(string $type, string $name): ResourceRequestInterface;

    /**
     * Contains the "sort" parameter.
     * The field name is always the key while the value always have to be ResourceRequestInterface::ORDER_ASC or ResourceRequestInterface::ORDER_DESC
     *
     * @return KeyValueCollectionInterface
     */
    public function sorting(): KeyValueCollectionInterface;

    /**
     * Contains the "page" parameter
     *
     * @return KeyValueCollectionInterface
     */
    public function pagination(): KeyValueCollectionInterface;

    /**
     * Contains the "filter" parameter
     *
     * @return KeyValueCollectionInterface
     */
    public function filter(): KeyValueCollectionInterface;
}
