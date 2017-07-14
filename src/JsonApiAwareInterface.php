<?php
declare(strict_types=1);

namespace Enm\JsonApi;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface JsonApiAwareInterface
{
    /**
     * @param JsonApiInterface $jsonApi
     * @return void
     */
    public function setJsonApi(JsonApiInterface $jsonApi);
}
