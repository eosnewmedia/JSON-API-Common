<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Document;

use Psr\Http\Message\UriInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class OffsetBasedPaginatedDocument extends Document
{
    private const SELF_LINK = 'self';
    private const FIRST_LINK = 'first';
    private const PREVIOUS_LINK = 'previous';
    private const NEXT_LINK = 'next';
    private const LAST_LINK = 'last';
    private const OFFSET = 'offset';
    private const LIMIT = 'limit';

    /**
     * @param array $data
     * @param UriInterface $requestUri
     * @param int $resultCount
     * @param int $defaultLimit
     */
    public function __construct(array $data, UriInterface $requestUri, int $resultCount, int $defaultLimit)
    {
        parent::__construct($data);

        $this->links()->createLink(self::SELF_LINK, (string)$requestUri);

        parse_str($requestUri->getQuery(), $query);
        $currentOffset = 0;
        $limit = $defaultLimit;
        if (array_key_exists('page', $query)) {
            if (array_key_exists(self::OFFSET, $query['page'])) {
                $currentOffset = (int)$query['page'][self::OFFSET];
            }
            if (array_key_exists(self::LIMIT, $query['page'])) {
                $limit = (int)$query['page'][self::LIMIT];
            }
        }

        if ($currentOffset !== 0) {
            $this->links()->createLink(
                self::FIRST_LINK,
                $this->createPaginatedUri($requestUri, 0, $limit)
            );
        }

        $previous = $currentOffset - $limit;
        if ($previous >= 0) {
            $this->links()->createLink(
                self::PREVIOUS_LINK,
                $this->createPaginatedUri($requestUri, $previous, $limit)
            );
        } elseif ($currentOffset !== 0) {
            $this->links()->createLink(
                self::PREVIOUS_LINK,
                $this->createPaginatedUri($requestUri, 0, $limit)
            );
        }

        $next = $currentOffset + $limit;

        if ($next < $resultCount) {
            $this->links()->createLink(
                self::NEXT_LINK,
                $this->createPaginatedUri($requestUri, $next, $limit)
            );
        }

        $last = $resultCount - $limit;
        if ($last > $currentOffset) {
            $this->links()->createLink(
                self::LAST_LINK,
                $this->createPaginatedUri($requestUri, $last, $limit)
            );
        }
    }

    /**
     * @param UriInterface $uri
     * @param int $offset
     * @param int $limit
     * @return string
     */
    protected function createPaginatedUri(UriInterface $uri, int $offset, int $limit): string
    {
        parse_str($uri->getQuery(), $query);

        $query['page'][self::OFFSET] = $offset;
        $query['page'][self::LIMIT] = $limit;

        return (string)$uri->withQuery(http_build_query($query));
    }
}
