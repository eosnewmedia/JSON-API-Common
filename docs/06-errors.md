[back to README](../README.md)
# Exceptions and Errors
The `JsonApiException` automatically creates an error collection by default and converts the current Exception context given in the constructor to the first error (`Enm\JsonApi\Model\Error\ErrorInterface`).

The instantiated collection can be retrieved and appended by calling the `errors()` method which returns an instance of `Enm\JsonApi\Model\Error\ErrorCollectionInterface`.

These exceptions are available to be handled including the correct http status code:

|  Exception                                            | Description                           |
|-------------------------------------------------------|---------------------------------------|
| `Enm\JsonApi\Exception\JsonApiException`              | For general server errors             |
| `Enm\JsonApi\Exception\BadRequestException`           | For client (request) errors           |
| `Enm\JsonApi\Exception\ResourceNotFoundException`     | For 404 errors on a concrete resource |
| `Enm\JsonApi\Exception\UnsupportedMediaTypeException` | For invalid content type header       |
| `Enm\JsonApi\Exception\UnsupportedTypeException`      | For 404 errors on a resource list     |
| `Enm\JsonApi\Exception\NotAllowedException`           | For 403 errors                        |
| `Enm\JsonApi\Exception\HttpException`                 | For custom http status codes          |

*****

[prev: Documents](../docs/05-documents.md) | [back to README](../README.md) | [next: Request and Response](../docs/07-requests.md)