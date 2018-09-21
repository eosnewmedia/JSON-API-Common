[back to README](../README.md)

# Request and Response

`Enm\JsonApi\Model\Request\RequestInterface`:

| Method       | Return Type                                                                        | Description                                                           |
|--------------|------------------------------------------------------------------------------------|-----------------------------------------------------------------------|
| type()       | string                                                                             | Contains the requested resource type.                                 |
| id()         | string                                                                             | Contains the requested id if available.                               |
| headers()    | [KeyValueCollectionInterface](../src/Model/Common/KeyValueCollectionInterface.php) | Contains all request headers.                                         |

*****

[prev: Errors and Exceptions](../docs/06-errors.md) | [back to README](../README.md)
