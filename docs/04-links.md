[back to README](../README.md)
# Links
Links for resources or documents are grouped through an object of type `Enm\JsonApi\Model\Resource\Link\LinkCollectionInterface`.

| Method                                 | Return type                                                      | Description                                                                                    |
|----------------------------------------|------------------------------------------------------------------|------------------------------------------------------------------------------------------------|
| all()                                  | array                                                            | All link objects of this collection.                                                           |
| count()                                | int                                                              | Number of collection entries.                                                                  |
| isEmpty()                              | bool                                                             | Checks if the collection contains any elements.                                                |
| has(string $name)                      | bool                                                             | Checks if the collection contains a special link.                                              |
| get(string $name)                      | [LinkInterface](../src/Model/Resource/Link/LinkInterface.php)    | Returns a link by name or throws an \InvalidArgumentException if relationship does not exists. |
| set(LinkInterface $link)               | $this                                                            | Set a link object into the collection.                                                         |
| remove(string $name)                   | $this                                                            | Remove a link by name from the collection.                                                     |
| removeElement(LinkInterface $link)     | $this                                                            | Remove a link object from the collection.                                                      |
| createLink(string $name, string $href) | $this                                                            | Create a new link object in the collection.                                                    |

A link itself is an object of type `Enm\JsonApi\Model\Resource\Link\LinkInterface`. 

| Method                         | Return type                                                                          | Description                                                       |
|--------------------------------|--------------------------------------------------------------------------------------|-------------------------------------------------------------------|
| getName()                      | string                                                                               | The link name.                                                    |
| getHref()                      | string                                                                               | The link target.                                                  |
| metaInformation()              | [KeyValueCollectionInterface](../src/Model/Common/KeyValueCollectionInterface.php)   | Collection of meta informations for this link.                    |
| duplicate(string $name = null) | $this                                                                                | Helper method to duplicate this linl, optional with another name. |

*****

[prev: Attributes and Meta](../docs/03-collections.md) | [back to README](../README.md) | [next: Documents](../docs/05-documents.md)
