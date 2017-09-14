<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Resource\Extension;

use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface RelatedMetaInformationInterface
{
    /**
     * This method provides additional meta information for a resource identifier object in the context of relationship data
     *
     * @return KeyValueCollectionInterface
     */
    public function relatedMetaInformation(): KeyValueCollectionInterface;
}
