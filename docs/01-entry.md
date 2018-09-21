[back to README](../README.md)
# Enm\JsonApi\JsonApiInterface

| Method                                                             | Return type                                                                              | Description                                            |
|--------------------------------------------------------------------|------------------------------------------------------------------------------------------|--------------------------------------------------------|
| generateUuid()                                                     | string                                                                                   | Create a new random uuid                               |
| resource(string $type, string $id)                                 | [ResourceInterface](../src/Model/Resource/ResourceInterface.php)                         | Create a new JSON resource                             |
| singleResourceDocument(ResourceInterface $resource = null)         | [DocumentInterface](../src/Model/Document/DocumentInterface.php)                         | Create a document which can contain one resource       |
| multiResourceDocument(array $resource = [])                        | [DocumentInterface](../src/Model/Document/DocumentInterface.php)                         | Create a document which can contain multiple resources |
| toOneRelationship(string $name, ResourceInterface $related = null) | [RelationshipInterface](../src/Model/Resource/Relationship/RelationshipInterface.php)    | Create a new to one relationship object                |
| toManyRelationship(string $name, array $related = [])              | [RelationshipInterface](../src/Model/Resource/Relationship/RelationshipInterface.php)    | Create a new to many relationship object               |

The simplest way of implementing the interface is to use the `Enm\JsonApi\JsonApiTrait`.

*****

[back to README](../README.md) | [next: Resources](../docs/02-resources.md)
