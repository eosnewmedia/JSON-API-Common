[back to README](../README.md)
# Resources
A json api resource is represented through a php object of type `Enm\JsonApi\Model\Resource\ResourceInterface` and requires at least "type" and "id".

`Enm\JsonApi\Model\Resource\ResourceInterface`:

| Method             | Return Type                     | Description                                       |
|--------------------|---------------------------------|---------------------------------------------------|
| type()             | string                          | Resource Type Identifier ("type")                 |
| id()               | string                          | Resource Identifier ("id")                        |
| attributes         | SimpleCollectionInterface       | Attributes of the resource ("attributes")         |
| relationships      | RelationshipCollectionInterface | The relationships of a resource ("relationships") |
| links()            | LinkCollectionInterface         | The links for a resource ("links")                |
| metaInformation()  | SimpleCollectionInterface       | Meta Informations for a resource ("meta")         |

## Relationships
A Relationship is represented through a php object of type `Enm\JsonApi\Model\Resource\Relationship\RelationshipInterface`:

| Method                         | Return Type                 | Description                                                                              |
|--------------------------------|-----------------------------|------------------------------------------------------------------------------------------|
| shouldBeHandledAsCollection()  | boolean                     | Indicates if the contained data should be handled as object collection or single object. |
| name()                         | string                      | The relationship name                                                                    |
| related()                      | ResourceCollectionInterface | Collection of related resources for this relationship.                                   |
| links()                        | LinkCollectionInterface     | Collection of link objects for this relationship.                                        |
| metaInformation()              | SimpleCollectionInterface   | Collection of meta informations for this relationship.                                   |
| duplicate(string $name = null) | $this                       | Helper method to duplicate this relationship, optional with another name.                |

A relationship contains, depending on return value of "shouldBeHandledAsCollection", one or many related resources or can be empty.

A relationship needs a unique name (in context of one resource) and offers access to all related resources via `RelationshipInterface::related()`.
Relationships can contain links and meta information like resources.

The relationships of a resource are accessible via `ResourceInterface::relationships()`, which is an instance of `Enm\JsonApi\Model\Resource\Relationship\RelationshipCollectionInterface`:

| Method                                                       | Return Type           | Description                                                                                            |
|--------------------------------------------------------------|-----------------------|--------------------------------------------------------------------------------------------------------|
| all()                                                        | array                 | All relationship objects of this collection.                                                           |
| count()                                                      | int                   | Number of collection entries.                                                                          |
| isEmpty()                                                    | bool                  | Checks if the collection contains any elements.                                                        |
| has(string $name)                                            | bool                  | Checks if the collection contains a special relationship.                                              |
| get(string $name)                                            | RelationshipInterface | Returns a relationship by name or throws an \InvalidArgumentException if relationship does not exists. |
| set(RelationshipInterface $relationship)                     | $this                 | Set a relationship object into the collection.                                                         |
| remove(string $name)                                         | $this                 | Remove a relationship by name from the collection.                                                     |
| removeElement(RelationshipInterface $relationship)           | $this                 | Remove a relationship object from the collection.                                                      |

*****

[back to Entry Point](../docs/01-entry.md) | [next: Attributes and Meta](../docs/03-collections.md)
