<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Factory;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */

interface DocumentFactoryAwareInterface
{
    /**
     * @param DocumentFactoryInterface $documentFactory
     * @return void
     */
    public function setDocumentFactory(DocumentFactoryInterface $documentFactory);
}
