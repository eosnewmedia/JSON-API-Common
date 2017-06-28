<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Document;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class RelationshipCollectionDocument extends ResourceCollectionDocument
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE_RELATIONSHIP_COLLECTION;
    }
}
