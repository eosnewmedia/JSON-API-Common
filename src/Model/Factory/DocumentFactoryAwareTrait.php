<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Factory;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
trait DocumentFactoryAwareTrait
{
    /**
     * @var DocumentFactoryInterface
     */
    private $documentFactory;

    /**
     * @param DocumentFactoryInterface $documentFactory
     *
     * @return void
     */
    public function setDocumentFactory(DocumentFactoryInterface $documentFactory)
    {
        $this->documentFactory = $documentFactory;
    }

    /**
     * @return DocumentFactoryInterface
     */
    protected function documentFactory(): DocumentFactoryInterface
    {
        if (!$this->documentFactory instanceof DocumentFactoryInterface) {
            $this->documentFactory = new DocumentFactory();
        }

        return $this->documentFactory;
    }
}
