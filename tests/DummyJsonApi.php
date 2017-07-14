<?php
declare(strict_types=1);

namespace Enm\JsonApi\Tests;

use Enm\JsonApi\JsonApiInterface;
use Enm\JsonApi\JsonApiTrait;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class DummyJsonApi implements JsonApiInterface
{
    use JsonApiTrait;
}
