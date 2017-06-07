[back to README](../README.md)
# Resources
A json api resource is represented through a php object of type `Enm\JsonApi\Model\Resource\ResourceInterface` and requires at least "type" and "id".

You can use this interface with your custom classes or may use the default implementation `Enm\JsonApi\Model\Resource\JsonResource`, which will be good choice for most use cases.

    $resource = new Enm\JsonApi\Model\Resource\JsonResource($resourceType, $resourceId);
    

`Enm\JsonApi\Model\Resource\ResourceInterface`:

| Method             | Return Type                     | Description                                       |
|--------------------|---------------------------------|---------------------------------------------------|
| getType()          | string                          | Resource Type Identifier ("type")                 |
| getId()            | string                          | Resource Identifier ("id")                        |
| attributes         | SimpleCollectionInterface       | Attributes of the resource ("attributes")         |
| relationships      | RelationshipCollectionInterface | The relationships of a resource ("relationships") |
| links()            | LinkCollectionInterface         | The links for a resource ("links")                |
| metaInformations() | SimpleCollectionInterface       | Meta Informations for a resource ("meta")         |

## Relationships
A Relationship is represented through a php object of type `Enm\JsonApi\Model\Resource\Relationship\RelationshipInterface`:

| Method                         | Return Type                 | Description                                                               |
|--------------------------------|-----------------------------|---------------------------------------------------------------------------|
| getType()                      | string                      | Type of the relationship ("one" or "many").                               |
| getName()                      | string                      | The relationship name                                                     |
| related()                      | ResourceCollectionInterface | Collection of related resources for this relationship.                    |
| links()                        | LinkCollectionInterface     | Collection of link objects for this relationship.                         |
| metaInformations()             | SimpleCollectionInterface   | Collection of meta informations for this relationship.                    |
| duplicate(string $name = null) | $this                       | Helper method to duplicate this relationship, optional with another name. |

A relationship contains, depending on type, one or many related resources or can be empty.

There are two types of relationships, `one` and `many`, with usable default implementations:

* `Enm\JsonApi\Model\Resource\Relationship\ToOneRelationship` (can contain zero or exactly one related resource)
* `Enm\JsonApi\Model\Resource\Relationship\RelationshipInterface` (can contain zero or many related resources)

A relationship needs a unique (in the context of one resource type) name offers access to all related resources via `RelationshipInterface::related()`.
A relationship can contain links and meta informations like a resource.

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
| createToOne(string $name, ResourceInterface $related = null) | $this                 | Create a new to-one-relationship with name and (optional) resource and set it into the collection.     |
| createToMany(string $name, array $related = [])              | $this                 | Create a new to-many-relationship with name an related resources and set it into the collection.       |

*****

[back to README](../README.md) | [next: Attributes and Meta](../docs/02-collections.md)