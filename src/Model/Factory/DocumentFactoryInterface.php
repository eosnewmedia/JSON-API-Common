<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Factory;

use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Document\JsonApi\JsonApiInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface DocumentFactoryInterface extends ResourceFactoryAwareInterface
{
    /**
     * @param mixed $data
     * @param string $version
     * @return DocumentInterface
     */
    public function create($data = null, string $version = JsonApiInterface::CURRENT_VERSION): DocumentInterface;
}
