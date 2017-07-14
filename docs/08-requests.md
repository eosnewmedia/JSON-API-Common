[back to README](../README.md)

# Requests

Request are the models used by json api to retrieve documents from a server.

`Enm\JsonApi\Model\Request\JsonApiRequestInterface`:

| Method       | Return Type                                                                        | Description                                                           |
|--------------|------------------------------------------------------------------------------------|-----------------------------------------------------------------------|
| type()       | string                                                                             | Contains the requested resource type.                                 |
| containsId() | bool                                                                               | Indicates if the request contains an id to request only one resource. |
| id()         | string                                                                             | Contains the requested id if available.                               |
| headers()    | [KeyValueCollectionInterface](../src/Model/Common/KeyValueCollectionInterface.php) | Contains all request headers.                                         |

`Enm\JsonApi\Model\Request\FetchRequestInterface` (extends `Enm\JsonApi\Model\Request\JsonApiRequestInterface`):

| Method                            | Return Type                                                                        | Description                                                                                                                                                                    |
|-----------------------------------|------------------------------------------------------------------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| includes()                        | array                                                                              | Contains the "include" parameter exploded by ",".                                                                                                                              |
| include(string $relationship)     | $this                                                                              | Includes a relationship on this request.                                                                                                                                       |
| fields()                          | array                                                                              | Contains the "fields" parameter. The resource type is always the key which contains an array of requested field names.                                                         |
| field(string $type, string $name) | $this                                                                              | Request a field for a type to be contained in the response.                                                                                                                    |
| sorting()                         | [KeyValueCollectionInterface](../src/Model/Common/KeyValueCollectionInterface.php) | Contains the "sort" parameter. The field name is always the key while the value always have to be ResourceRequestInterface::ORDER_ASC or ResourceRequestInterface::ORDER_DESC. |
| pagination()                      | [KeyValueCollectionInterface](../src/Model/Common/KeyValueCollectionInterface.php) | Contains the "page" parameter.                                                                                                                                                 |
| filter()                          | [KeyValueCollectionInterface](../src/Model/Common/KeyValueCollectionInterface.php) | Contains the "filter" parameter.                                                                                                                                               |

`Enm\JsonApi\Model\Request\SaveRequestInterface` (extends `Enm\JsonApi\Model\Request\JsonApiRequestInterface`):

| Method     | Return Type                                                      | Description                                                       |
|------------|------------------------------------------------------------------|-------------------------------------------------------------------|
| document() | [DocumentInterface](../src/Model/Document/DocumentInterface.php) | Contains the request document which contains the resource to save |

*****

[prev: Factories](../docs/07-factories.md) | [back to README](../README.md)
