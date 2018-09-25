<?php
declare(strict_types=1);

namespace Enm\JsonApi\Mapper;

use Enm\JsonApi\Model\Request\RequestInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface ObjectMapperInterface
{
    /**
     * @param ResourceInterface $resource
     * @param object $object
     * @param RequestInterface $request
     */
    public function mapResource(ResourceInterface $resource, object $object, RequestInterface $request): void;
}
