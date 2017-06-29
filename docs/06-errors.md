[back to README](../README.md)
# Exceptions and Errors
Exceptions should turned into instances of `Enm\JsonApi\Model\Error\ErrorInterface` if you want to serialize errors for output.

The simplest way is to use the default implementation `Enm\JsonApi\Model\Error\Error`, which offers a static method to create an 
error object from an exception.

```php
Enm\JsonApi\Model\Error\Error::createFromException(new \Exception());
```

The following Exception are available to be handled including the correct http status code:

* `Enm\JsonApi\Exception\Exception`: For general server errors
* `Enm\JsonApi\Exception\InvalidRequestException`: For client (request) errors
* `Enm\JsonApi\Exception\ResourceNotFoundException`: For 404 errors on a concrete resource
* `Enm\JsonApi\Exception\UnsupportedMediaTypeException`: For invalid content type header
* `Enm\JsonApi\Exception\UnsupportedTypeException`: For 404 errors on a resource list
* `Enm\JsonApi\Exception\NotAllowedException`: For 403 errors
* `Enm\JsonApi\Exception\HttpException`: For custom http status codes

# Document errors
Document errors are grouped within their document by an object of type `Enm\JsonApi\Model\Error\ErrorCollectionInterface`.

*****

[prev: Documents](../docs/05-documents.md) | [back to README](../README.md) | [next: Factories](../docs/07-factories.md)
