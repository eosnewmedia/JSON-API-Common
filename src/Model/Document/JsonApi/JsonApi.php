<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Document\JsonApi;

use Enm\JsonApi\Model\Common\KeyValueCollection;
use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class JsonApi implements JsonApiInterface
{
    /**
     * @var string
     */
    private $version;

    /**
     * @var KeyValueCollectionInterface
     */
    private $metaInformation;

    /**
     * @param string $version
     */
    public function __construct(string $version = self::CURRENT_VERSION)
    {
        $this->version = $version;
        $this->metaInformation = new KeyValueCollection();
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return KeyValueCollectionInterface
     */
    public function metaInformation(): KeyValueCollectionInterface
    {
        return $this->metaInformation;
    }
}
