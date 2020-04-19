[back to README](../README.md)
# Resources
A JSON API resource is represented through a PHP object of type `Enm\JsonApi\Model\Resource\ResourceInterface` and requires at least "type".

`Enm\JsonApi\Model\Resource\ResourceInterface`:

| Method             | Return type                                                                                                  | Description                                       |
|--------------------|--------------------------------------------------------------------------------------------------------------|---------------------------------------------------|
| type()             | string                                                                                                       | Resource Type Identifier ("type")                 |
| id()               | string or null                                                                                               | Resource Identifier ("id")                        |
| attributes()       | [KeyValueCollection](../src/Model/Common/KeyValueCollection.php)                                             | Attributes of the resource ("attributes")         |
| relationships()    | [RelationshipCollectionInterface](../src/Model/Resource/Relationship/RelationshipCollectionInterface.php)    | The relationships of a resource ("relationships") |
| links()            | [LinkCollectionInterface](../src/Model/Resource/Link/LinkCollectionInterface.php)                            | The links for a resource ("links")                |
| metaInformation()  | [KeyValueCollection](../src/Model/Common/KeyValueCollection.php)                                             | Meta Informations for a resource ("meta")         |

## Relationships
A Relationship is represented through a PHP object of type `Enm\JsonApi\Model\Resource\Relationship\RelationshipInterface`:

| Method                         | Return type                                                                              | Description                                                                              |
|--------------------------------|------------------------------------------------------------------------------------------|------------------------------------------------------------------------------------------|
| shouldBeHandledAsCollection()  | boolean                                                                                  | Indicates if the contained data should be handled as object collection or single object. |
| name()                         | string                                                                                   | The relationship name                                                                    |
| related()                      | [ResourceCollectionInterface](../src/Model/Resource/ResourceCollectionInterface.php)     | Collection of related resources for this relationship.                                   |
| links()                        | [LinkCollectionInterface](../src/Model/Resource/Link/LinkCollectionInterface.php)        | Collection of link objects for this relationship.                                        |
| metaInformation()              | [KeyValueCollection](../src/Model/Common/KeyValueCollection.php)                         | Collection of meta informations for this relationship.                                   |
| duplicate(string $name = null) | [RelationshipInterface](../src/Model/Resource/Relationship/RelationshipInterface.php)    | Helper method to duplicate this relationship, optional with another name.                |

A relationship contains, depending on return value of "shouldBeHandledAsCollection", one or many related resources or can be empty.

A relationship needs a unique name (in context of one resource) and offers access to all related resources via `RelationshipInterface::related()`.
Relationships can contain links and meta information like resources.

The relationships of a resource are accessible via `ResourceInterface::relationships()`, which is an instance of `Enm\JsonApi\Model\Resource\Relationship\RelationshipCollectionInterface`:

| Method                                                       | Return type                                                                            | Description                                                                                            |
|--------------------------------------------------------------|----------------------------------------------------------------------------------------|--------------------------------------------------------------------------------------------------------|
| all()                                                        | array                                                                                  | All relationship objects of this collection.                                                           |
| count()                                                      | int                                                                                    | Number of collection entries.                                                                          |
| isEmpty()                                                    | bool                                                                                   | Checks if the collection contains any elements.                                                        |
| has(string $name)                                            | bool                                                                                   | Checks if the collection contains a special relationship.                                              |
| get(string $name)                                            | [RelationshipInterface](../src/Model/Resource/Relationship/RelationshipInterface.php)  | Returns a relationship by name or throws an \InvalidArgumentException if relationship does not exists. |
| set(RelationshipInterface $relationship)                     | $this                                                                                  | Set a relationship object into the collection.                                                         |
| remove(string $name)                                         | $this                                                                                  | Remove a relationship by name from the collection.                                                     |
| removeElement(RelationshipInterface $relationship)           | $this                                                                                  | Remove a relationship object from the collection.                                                      |


### Related Resource Meta Information
If a related resource should contain meta information which should only be accessible in the resource identifier object in the relationship,
your Resource can implement `Enm\JsonApi\Model\Resource\Extension\RelatedMetaInformationInterface` and use `Enm\JsonApi\Model\Resource\Extension\RelatedMetaInformationTrait`.

*****

[back to Entry Point](../docs/01-entry.md) | [next: Attributes and Meta](../docs/03-collections.md)
