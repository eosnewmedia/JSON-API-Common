[back to README](../README.md)
# Links
Links for a resource or document are grouped through an object of type `Enm\JsonApi\Model\Resource\Link\LinkCollectionInterface`.

| Method                             | Return Type   | Description                                                                                    |
|------------------------------------|---------------|------------------------------------------------------------------------------------------------|
| all()                              | array         | All link objects of this collection.                                                           |
| count()                            | int           | Number of collection entries.                                                                  |
| isEmpty()                          | bool          | Checks if the collection contains any elements.                                                |
| has(string $name)                  | bool          | Checks if the collection contains a special link.                                              |
| get(string $name)                  | LinkInterface | Returns a link by name or throws an \InvalidArgumentException if relationship does not exists. |
| set(LinkInterface $link)           | $this         | Set a link object into the collection.                                                         |
| remove(string $name)               | $this         | Remove a link by name from the collection.                                                     |
| removeElement(LinkInterface $link) | $this         | Remove a link object from the collection.                                                      |

A link it self is an object of type `Enm\JsonApi\Model\Resource\Link\LinkInterface`. 

| Method                         | Return Type               | Description                                                       |
|--------------------------------|---------------------------|-------------------------------------------------------------------|
| getName()                      | string                    | The link name.                                                    |
| getHref()                      | string                    | The link target.                                                  |
| metaInformations()             | SimpleCollectionInterface | Collection of meta informations for this link.                    |
| duplicate(string $name = null) | $this                     | Helper method to duplicate this linl, optional with another name. |

There is a default implementation for most use cases: `Enm\JsonApi\Model\Resource\Link\Link`.

*****

[prev: Attributes and Meta](../docs/02-collections.md) | [back to README](../README.md) | [next: Documents](../docs/04-documents.md)
