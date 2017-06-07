[back to README](../README.md)
# Exceptions and Errors
Exceptions should turned into instances of `Enm\JsonApi\Model\Error\ErrorInterface` to handle it json api like.

The simplest way is to use the default implementation `Enm\JsonApi\Model\Error\Error`, which offers a static method to create an 
error object from an exception.

    Enm\JsonApi\Model\Error\Error::createFromException(new \Exception());

The following Exception are available to be handled including the correct http status code:

* `Enm\JsonApi\Exception\Exception`: For general server errors
* `Enm\JsonApi\Exception\InvalidRequestException`: For client (request) errors
* `Enm\JsonApi\Exception\ResourceNotFoundException`: For 404 errors on a concrete resource
* `Enm\JsonApi\Exception\UnsupportedMediaTypeException`: For invalid content type header
* `Enm\JsonApi\Exception\UnsupportedTypeException`: For 404 errors on a resource list
* `Enm\JsonApi\Exception\NotAllowedException`: For 403 errors
* `Enm\JsonApi\Exception\HttpException`: For custom http status codes

*****

[prev: Documents](../docs/04-documents.md) | [back to README](../README.md)
