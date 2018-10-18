<?php declare(strict_types=1);

namespace Enm\JsonApi\Model;

class JsonApi
{
    public const CONTENT_TYPE = 'application/vnd.api+json';

    private function __construct()
    {
        // private constructor to disallow class instantiation
    }
}
