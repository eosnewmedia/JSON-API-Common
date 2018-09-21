<?php
declare(strict_types=1);

namespace Enm\JsonApi\Mapper;

use Enm\JsonApi\Model\Request\RequestInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ObjectResourceMapper implements ObjectMapperInterface, ResourceMapperInterface
{
    /**
     * @var ObjectMapperInterface[]
     */
    private $objectMappers = [];

    /**
     * @var ResourceMapperInterface[]
     */
    private $resourceMappers = [];

    /**
     * @param ObjectMapperInterface $mapper
     */
    public function addObjectMapper(ObjectMapperInterface $mapper): void
    {
        $this->objectMappers[] = $mapper;
    }

    /**
     * @param ResourceMapperInterface $mapper
     */
    public function addResourceMapper(ResourceMapperInterface $mapper): void
    {
        $this->resourceMappers[] = $mapper;
    }

    /**
     * @param ResourceInterface $resource
     * @param object $object
     * @param RequestInterface $request
     */
    public function mapResource(ResourceInterface $resource, object $object, RequestInterface $request): void
    {
        foreach ($this->objectMappers as $mapper) {
            $mapper->mapResource($resource, $object, $request);
        }
    }

    /**
     * @param object $object
     * @param ResourceInterface $resource
     * @param RequestInterface $request
     */
    public function mapObject(object $object, ResourceInterface $resource, RequestInterface $request): void
    {
        foreach ($this->resourceMappers as $mapper) {
            $mapper->mapObject($object, $resource, $request);
        }
    }
}
