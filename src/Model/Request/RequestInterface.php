<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Request;

use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;
use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;
use Psr\Http\Message\UriInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface RequestInterface
{
    public const ORDER_ASC = 'asc';
    public const ORDER_DESC = 'desc';

    /**
     * @return string
     */
    public function method(): string;

    /**
     * @return UriInterface
     */
    public function uri(): UriInterface;

    /**
     * Contains all request headers
     *
     * @return KeyValueCollectionInterface
     */
    public function headers(): KeyValueCollectionInterface;

    /**
     * Contains the requested resource type
     *
     * @return string
     */
    public function type(): string;

    /**
     * @return string|null
     */
    public function id(): ?string;

    /**
     * @return string|null
     */
    public function relationship(): ?string;

    /**
     * Indicates if the response for this request should contain attributes for a resource
     * @return bool
     */
    public function requestsAttributes(): bool;

    /**
     * Indicates if the response for this request should contain meta information for a resource
     * @return bool
     */
    public function requestsMetaInformation(): bool;

    /**
     * Indicates if the response for this request should contain relationships for a resource
     * @return bool
     */
    public function requestsRelationships(): bool;

    /**
     * Define a field as requested. This method will manipulate the uri of the request.
     * @param string $type
     * @param string $name
     */
    public function requestField(string $type, string $name): void;

    /**
     * Indicates if the response should contain the given field for the given type.
     * @param string $type
     * @param string $name
     * @return bool
     */
    public function requestsField(string $type, string $name): bool;

    /**
     * Define a relationship as included. This method will manipulate the uri of the request.
     * @param string $relationship
     */
    public function requestInclude(string $relationship): void;

    /**
     * Indicates if a response should include the given relationship.
     * @param string $relationship
     * @return bool
     */
    public function requestsInclude(string $relationship): bool;

    /**
     * Define a filter value. This method will manipulate the uri of the request.
     * @param string $name
     * @param array|string|int|float $value
     * @return void
     */
    public function addFilter(string $name, $value): void;

    /**
     * Indicates if the given filter is available.
     * @param string $name
     * @return bool
     */
    public function hasFilter(string $name): bool;

    /**
     * Retrieve the value for the given filter.
     * @param string $name
     * @param string|null $explodeBy
     * @return array|string|int|float
     */
    public function filterValue(string $name, string $explodeBy = null);

    /**
     * Define a sort parameter. This method will manipulate the uri of the request.
     * @param string $name
     * @param string $direction
     */
    public function addOrderBy(string $name, string $direction = self::ORDER_ASC): void;

    /**
     * The field name is always the key while the value always have to be self::ORDER_ASC or self::ORDER_DESC
     * @return array
     */
    public function order(): array;

    /**
     * Define a pagination parameter. This method will manipulate the uri of the request.
     * @param string $key
     * @param array|string|int|float $value
     */
    public function addPagination(string $key, $value): void;

    /**
     * Indicates if the given pagination parameter is available.
     * @param string $key
     * @return bool
     */
    public function hasPagination(string $key): bool;

    /**
     * Retrieve a pagination value.
     * @param string $key
     * @return array|string|int|float
     */
    public function paginationValue(string $key);

    /**
     * Retrieve the request body if available.
     * @return DocumentInterface|null
     */
    public function requestBody(): ?DocumentInterface;

    /**
     * Creates a request for the given relationship.
     * If called twice, the call will return the already created sub request.
     * A sub request does not contain pagination and sorting from its parent.
     *
     * @param string $relationship
     * @param ResourceInterface|null $resource
     * @param bool $keepFilters
     * @return RequestInterface
     */
    public function createSubRequest(
        string $relationship,
        ?ResourceInterface $resource = null,
        bool $keepFilters = false
    ): RequestInterface;
}
