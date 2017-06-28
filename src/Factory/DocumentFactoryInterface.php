<?php
declare(strict_types=1);

namespace Enm\JsonApi\Factory;

use Enm\JsonApi\Model\Document\DocumentInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface DocumentFactoryInterface
{
    /**
     * @param string $type
     * @param mixed $data
     * @return DocumentInterface
     */
    public function create(string $type, $data = null): DocumentInterface;
}
