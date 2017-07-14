<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Factory;

use Enm\JsonApi\Model\Resource\ResourceInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ResourceFactoryRegistry implements ResourceFactoryInterface
{
    /**
     * @var ResourceFactoryInterface
     */
    private $defaultFactory;

    /**
     * @var ResourceFactoryInterface[]
     */
    private $resourceFactories = [];

    /**
     * @param ResourceFactoryInterface $defaultFactory
     *
     * @return void
     */
    public function setDefaultFactory(ResourceFactoryInterface $defaultFactory)
    {
        $this->defaultFactory = $defaultFactory;
    }

    /**
     * @param string $type
     * @param ResourceFactoryInterface $resourceFactory
     *
     * @return void
     */
    public function addResourceFactory(string $type, ResourceFactoryInterface $resourceFactory)
    {
        $this->resourceFactories[$type] = $resourceFactory;
    }

    /**
     * @param string $type
     * @param string $id
     * @return ResourceInterface
     */
    public function create(string $type, string $id): ResourceInterface
    {
        if (array_key_exists($type, $this->resourceFactories)) {
            return $this->resourceFactories[$type]->create($type, $id);
        }

        return $this->defaultFactory->create($type, $id);
    }
}
