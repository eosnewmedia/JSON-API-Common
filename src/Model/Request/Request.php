<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Request;

use Enm\JsonApi\Exception\BadRequestException;
use Enm\JsonApi\Exception\UnsupportedMediaTypeException;
use Enm\JsonApi\Model\Common\KeyValueCollection;
use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;
use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\JsonApi;
use Enm\JsonApi\Model\Resource\ResourceInterface;
use Psr\Http\Message\UriInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class Request implements RequestInterface
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var UriInterface
     */
    private $uri;

    /**
     * @var string|null
     */
    private $apiPrefix;

    /**
     * @var string|null
     */
    private $fileInPath;

    /**
     * @var KeyValueCollectionInterface
     */
    private $headers;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $relationship;

    /**
     * @var bool
     */
    private $requestsAttributes = true;

    /**
     * @var bool
     */
    private $requestsMetaInformation = true;

    /**
     * @var array
     */
    private $fields;

    /**
     * @var array
     */
    private $includes;

    /**
     * @var array
     */
    private $currentLevelIncludes;

    /**
     * @var array
     */
    private $filter;

    /**
     * @var array
     */
    private $order;

    /**
     * @var array
     */
    private $pagination;

    /**
     * @var DocumentInterface|null
     */
    private $requestBody;

    /**
     * @var RequestInterface[]
     */
    private $subRequests = [];

    /**
     * @param string $method
     * @param UriInterface $uri
     * @param DocumentInterface|null $requestBody
     * @param null|string $apiPrefix
     * @throws BadRequestException
     */
    public function __construct(
        string $method,
        UriInterface $uri,
        ?DocumentInterface $requestBody = null,
        ?string $apiPrefix = null
    ) {
        $this->method = strtoupper($method);
        if (!\in_array($this->method, ['GET', 'POST', 'PATCH', 'DELETE'], true)) {
            throw new BadRequestException('Invalid http method');
        }
        $this->uri = $uri;
        $this->requestBody = $requestBody;
        $this->apiPrefix = $apiPrefix ? trim($apiPrefix, '/') : null;

        $this->parseUriPath($this->uri->getPath());
        $this->parseUriQuery($this->uri->getQuery());

        $this->headers = new KeyValueCollection();
        $this->headers->set('Content-Type', JsonApi::CONTENT_TYPE);
    }

    /**
     * @param string $path
     * @throws BadRequestException
     */
    private function parseUriPath(string $path): void
    {
        preg_match(
            '/^(([a-zA-Z0-9\_\-\.\/]+.php)(\/)|)(' . $this->apiPrefix . ')([\/a-zA-Z0-9\_\-\.]+)$/',
            trim($path, '/'),
            $matches
        );

        if (array_key_exists(3, $matches)) {
            $this->fileInPath = $matches[3];
        }
        if (!array_key_exists(5, $matches)) {
            $matches[5] = '';
        }

        $segments = explode('/', trim($matches[5], '/'));
        // fill missing segments
        while (\count($segments) < 4) {
            $segments[] = null;
        }

        $this->type = $segments[0];
        if (!$this->type) {
            throw new BadRequestException('Resource type missing.');
        }
        $this->id = $segments[1];
        if ($this->id === '') {
            throw new BadRequestException('Invalid resource id given.');
        }

        if ($this->id) {
            // parse relationship/related request
            if ($segments[3]) {
                if ($segments[2] !== 'relationships') {
                    throw new BadRequestException('Invalid relationship request!');
                }
                $this->requestsAttributes = false;
                $this->requestsMetaInformation = false;
                $this->relationship = (string)$segments[3];
            } elseif ($segments[2]) {
                $this->relationship = (string)$segments[2];
            }
        }
    }

    /**
     * @param string $uriQuery
     * @throws BadRequestException
     */
    private function parseUriQuery(string $uriQuery): void
    {
        parse_str($uriQuery, $query);
        $query = new KeyValueCollection($query);

        $this->includes = [];
        if ($query->has('include')) {
            if (!\is_string($query->getRequired('include'))) {
                throw new BadRequestException('Invalid include parameter given!');
            }

            $this->includes = explode(',', $query->getRequired('include'));
            foreach ($this->includes as $include) {
                $this->currentLevelIncludes[] = explode('.', $include)[0];
            }
        }

        $this->fields = [];
        if ($query->has('fields')) {
            if (!\is_array($query->getRequired('fields'))) {
                throw new BadRequestException('Invalid fields parameter given!');
            }
            foreach ((array)$query->getRequired('fields') as $type => $fields) {
                foreach (explode(',', $fields) as $field) {
                    $this->fields[$type][] = $field;
                }
            }
        }

        $this->filter = [];
        if ($query->has('filter')) {
            $filter = $query->getRequired('filter');
            if (\is_string($filter)) {
                $filter = json_decode($query->getRequired('filter'), true);
            }
            if (!\is_array($filter)) {
                throw new BadRequestException('Invalid filter parameter given!');
            }
            $this->filter = $filter;
        }

        $this->pagination = [];
        if ($query->has('page')) {
            if (!\is_array($query->getRequired('page'))) {
                throw new BadRequestException('Invalid page parameter given!');
            }
            $this->pagination = (array)$query->getRequired('page');
        }

        $this->order = [];
        if ($query->has('sort')) {
            if (!\is_string($query->getRequired('sort'))) {
                throw new BadRequestException('Invalid sort parameter given!');
            }
            foreach (explode(',', $query->getRequired('sort')) as $field) {
                $direction = self::ORDER_ASC;
                if (strpos($field, '-') === 0) {
                    $field = substr($field, 1);
                    $direction = self::ORDER_DESC;
                }
                $this->order[$field] = $direction;
            }
        }
    }

    /**
     * Updates the uri query
     */
    private function updateUriQuery(): void
    {
        $sort = [];
        foreach ($this->order as $field => $direction) {
            if ($direction === self::ORDER_ASC) {
                $sort[] = $field;
            } elseif ($direction === self::ORDER_DESC) {
                $sort[] = '-' . $field;
            }
        }

        $fields = [];
        foreach ($this->fields as $type => $fields) {
            $fields[$type] = implode(',', $fields);
        }

        $query = [
            'sort' => implode(',', $sort),
            'pagination' => $this->pagination,
            'filter' => $this->filter,
            'include' => implode(',', $this->includes),
            'fields' => $fields
        ];

        $this->uri = $this->uri()->withQuery(http_build_query($query));
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param DocumentInterface|null $requestBody
     * @param string|null $apiPrefix
     * @return Request
     * @throws BadRequestException|UnsupportedMediaTypeException
     */
    public static function createFromHttpRequest(
        \Psr\Http\Message\RequestInterface $request,
        ?DocumentInterface $requestBody,
        ?string $apiPrefix
    ): self {
        $apiRequest = new self($request->getMethod(), $request->getUri(), $requestBody, $apiPrefix);

        foreach ($request->getHeaders() as $header => $values) {
            $apiRequest->headers()->set($header, \count($values) !== 1 ? $values : $values[0]);
        }

        if ($apiRequest->headers()->getRequired('content-type') !== JsonApi::CONTENT_TYPE) {
            throw new UnsupportedMediaTypeException($apiRequest->headers()->getRequired('content-type'));
        }

        return $apiRequest;
    }

    /**
     * @return string
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * @return UriInterface
     */
    public function uri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * Contains all request headers
     *
     * @return KeyValueCollectionInterface
     */
    public function headers(): KeyValueCollectionInterface
    {
        return $this->headers;
    }

    /**
     * Contains the requested resource type
     *
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function id(): ?string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function relationship(): ?string
    {
        return $this->relationship;
    }

    /**
     * Indicates if the response for this request should contain attributes for a resource
     * @return bool
     */
    public function requestsAttributes(): bool
    {
        return $this->requestsAttributes;
    }

    /**
     * Indicates if the response for this request should contain meta information for a resource
     * @return bool
     */
    public function requestsMetaInformation(): bool
    {
        return $this->requestsMetaInformation;
    }

    /**
     * Indicates if the response for this request should contain relationships for a resource
     * @return bool
     */
    public function requestsRelationships(): bool
    {
        return ($this->requestsAttributes() || $this->requestsMetaInformation()) || \count($this->includes) > 0;
    }

    /**
     * Define a field as requested. This method will manipulate the uri of the request.
     * @param string $type
     * @param string $name
     */
    public function requestField(string $type, string $name): void
    {
        $this->fields[$type][] = $name;
        $this->updateUriQuery();
    }

    /**
     * @param string $type
     * @param string $name
     * @return bool
     */
    public function requestsField(string $type, string $name): bool
    {
        if (!array_key_exists($type, $this->fields)) {
            return true;
        }

        return \in_array($name, $this->fields[$type], true);
    }

    /**
     * Define a relationship as included. This method will manipulate the uri of the request.
     * @param string $relationship
     */
    public function requestInclude(string $relationship): void
    {
        $this->includes[] = $relationship;
        $this->currentLevelIncludes[] = explode('.', $relationship)[0];
        $this->updateUriQuery();
    }

    /**
     * @param string $relationship
     * @return bool
     */
    public function requestsInclude(string $relationship): bool
    {
        if (\in_array($relationship, $this->currentLevelIncludes, true)) {
            return true;
        }

        return \in_array($relationship, $this->includes, true);
    }

    /**
     * Define a filter value. This method will manipulate the uri of the request.
     * @param string $name
     * @param array|string|int|float $value
     * @return void
     */
    public function addFilter(string $name, $value): void
    {
        $this->filter[$name] = $value;
        $this->updateUriQuery();
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasFilter(string $name): bool
    {
        return array_key_exists($name, $this->filter);
    }

    /**
     * @param string $name
     * @param string|null $explodeBy
     * @return array|string|int|float
     */
    public function filterValue(string $name, string $explodeBy = null)
    {
        if ($explodeBy) {
            return explode($explodeBy, $this->filter[$name]);
        }

        return $this->filter[$name];
    }

    /**
     * Define a sort parameter. This method will manipulate the uri of the request.
     * @param string $name
     * @param string $direction
     */
    public function addOrderBy(string $name, string $direction = self::ORDER_ASC): void
    {
        $this->order[$name] = $direction;
        $this->updateUriQuery();
    }

    /**
     * The field name is always the key while the value always have to be self::ORDER_ASC or self::ORDER_DESC
     * @return array
     */
    public function order(): array
    {
        return $this->order;
    }

    /**
     * Define a pagination parameter. This method will manipulate the uri of the request.
     * @param string $key
     * @param array|string|int|float $value
     */
    public function addPagination(string $key, $value): void
    {
        $this->pagination[$key] = $value;
        $this->updateUriQuery();
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasPagination(string $key): bool
    {
        return array_key_exists($key, $this->pagination);
    }

    /**
     * @param string $key
     * @return array|string|int|float
     */
    public function paginationValue(string $key)
    {
        return $this->pagination[$key];
    }

    /**
     * @return DocumentInterface|null
     */
    public function requestBody(): ?DocumentInterface
    {
        return $this->requestBody;
    }

    /**
     * Creates a request for the given relationship.
     * If called twice, the call will return the already created sub request.
     * A sub request does not contain pagination and sorting from its parent.
     *
     * @param string $relationship
     * @param ResourceInterface|null $resource
     * @param bool $keepFilters
     * @return RequestInterface
     * @throws BadRequestException
     */
    public function createSubRequest(
        string $relationship,
        ?ResourceInterface $resource = null,
        bool $keepFilters = false
    ): RequestInterface {
        $requestKey = $relationship . ($keepFilters ? '-filtered' : '-not-filtered');
        if (!\array_key_exists($requestKey, $this->subRequests)) {
            $includes = [];
            foreach ($this->includes as $include) {
                if (strpos($include, '.') !== false && strpos($include, $relationship . '.') === 0) {
                    $includes[] = explode('.', $include, 2)[1];
                }
            }

            $queryFields = [];
            foreach ($this->fields as $type => $fields) {
                $queryFields[$type] = implode(',', $fields);
            }

            $type = $resource ? $resource->type() : $this->type();
            $id = $resource ? $resource->id() : $this->id();
            $relationshipPart = '/' . $relationship;
            if (!$this->requestsInclude($relationship)) {
                $relationshipPart = '/relationships' . $relationshipPart;
            }

            $subRequest = new self(
                $this->method(),
                $this->uri()
                    ->withPath(($this->fileInPath ? '/' . $this->fileInPath : '') . ($this->apiPrefix ? '/' . $this->apiPrefix : '') . '/' . $type . '/' . $id . $relationshipPart)
                    ->withQuery(
                        http_build_query([
                            'fields' => $queryFields,
                            'filter' => $keepFilters ? $this->filter : [],
                            'include' => implode(',', $includes)
                        ])
                    ),
                null,
                $this->apiPrefix
            );
            $subRequest->headers = $this->headers;

            $this->subRequests[$requestKey] = $subRequest;
        }

        return $this->subRequests[$requestKey];
    }
}
