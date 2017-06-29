<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Common;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */

interface OneOrManyInterface
{
    /**
     * Indicates if the contained data should be handled as object collection or single object
     *
     * @return bool
     */
    public function shouldBeHandledAsCollection(): bool;
}
