<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Resource\Extension;

use Enm\JsonApi\Model\Common\KeyValueCollection;
use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
trait RelatedMetaInformationTrait
{
    /**
     * @var KeyValueCollectionInterface
     */
    private $relatedMetaInformation;

    /**
     * This method provides additional meta information for a resource identifier object in the context of relationship data
     *
     * @return KeyValueCollectionInterface
     */
    public function relatedMetaInformation(): KeyValueCollectionInterface
    {
        if (!$this->relatedMetaInformation instanceof KeyValueCollectionInterface) {
            $this->relatedMetaInformation = new KeyValueCollection();
        }

        return $this->relatedMetaInformation;
    }
}
