<?php
declare(strict_types=1);

namespace Enm\JsonApi\Mapper;

use Enm\JsonApi\Model\Request\RequestInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface ResourceMapperInterface
{
    /**
     * @param object $object
     * @param ResourceInterface $resource
     * @param RequestInterface $request
     */
    public function mapObject(object $object, ResourceInterface $resource, RequestInterface $request): void;
}
