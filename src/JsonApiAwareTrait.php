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
    public function setJsonApi(JsonApiInterface $jsonApi)
    {
        $this->jsonApi = $jsonApi;
    }

    /**
     * @return JsonApiInterface
     * @throws \RuntimeException
     */
    protected function jsonApi(): JsonApiInterface
    {
        if (!$this->jsonApi instanceof JsonApiInterface) {
            throw new \RuntimeException('JsonApi not available!');
        }

        return $this->jsonApi;
    }
}
