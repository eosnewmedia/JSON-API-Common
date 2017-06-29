<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Factory;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
trait ResourceFactoryAwareTrait
{
    /**
     * @var ResourceFactoryInterface
     */
    private $resourceFactory;

    /**
     * @param ResourceFactoryInterface $resourceFactory
     *
     * @return void
     */
    public function setResourceFactory(ResourceFactoryInterface $resourceFactory)
    {
        $this->resourceFactory = $resourceFactory;

    }

    /**
     * @return ResourceFactoryInterface
     */
    protected function resourceFactory(): ResourceFactoryInterface
    {
        if (!$this->resourceFactory instanceof ResourceFactoryInterface) {
            $this->resourceFactory = new ResourceFactory();
        }

        return $this->resourceFactory;
    }
}
