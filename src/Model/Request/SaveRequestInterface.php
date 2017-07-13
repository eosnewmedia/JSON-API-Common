<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Request;

use Enm\JsonApi\Model\Document\DocumentInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface SaveRequestInterface extends JsonApiRequestInterface
{
    /**
     * Contains the request document which contains the resource to save
     *
     * @return DocumentInterface
     */
    public function document(): DocumentInterface;
}
