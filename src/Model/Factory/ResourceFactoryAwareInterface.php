<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Factory;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface ResourceFactoryAwareInterface
{
    /**
     * @param ResourceFactoryInterface $resourceFactory
     * @return void
     */
    public function setResourceFactory(ResourceFactoryInterface $resourceFactory);
}
