<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Document;

use Enm\JsonApi\Model\Resource\ImmutableResourceCollection;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ErrorDocument extends AbstractDocument
{
    /**
     * @param array $errors
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $errors = [])
    {
        parent::__construct(new ImmutableResourceCollection());
        foreach ($errors as $error) {
            $this->errors()->add($error);
        }
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE_ERROR;
    }
}
