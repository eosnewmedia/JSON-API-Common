<?php
declare(strict_types = 1);

namespace Enm\JsonApi\Model\Document;

use Enm\JsonApi\Model\Resource\ImmutableResourceCollection;
use Enm\JsonApi\Model\Resource\ResourceCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ErrorDocument extends AbstractDocument
{
    /**
     * @var ImmutableResourceCollection
     */
    private $data;
    
    /**
     * @param array $errors
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $errors = [])
    {
        parent::__construct();
        $this->data = new ImmutableResourceCollection();
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
    
    /**
     * @return ResourceCollectionInterface
     */
    public function data(): ResourceCollectionInterface
    {
        return $this->data;
    }
}
