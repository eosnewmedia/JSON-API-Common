<?php
declare(strict_types=1);

namespace Enm\JsonApi;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
trait JsonApiAwareTrait
{
    /**
     * @var JsonApiInterface
     */
    private $jsonApi;

    /**
     * @param JsonApiInterface $jsonApi
     *
     * @return void
     */
    public function setJsonApi(JsonApiInterface $jsonApi): void
    {
        $this->jsonApi = $jsonApi;
    }

    /**
     * @return JsonApiInterface
     */
    protected function jsonApi(): JsonApiInterface
    {
        return $this->jsonApi;
    }
}
