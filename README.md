ENM / JSON API / Common (PHP Library)
=====================================
This library contains php classes and interfaces shared between `enm/json-api-server` and `enm/json-api-client`.

## Table of Contents

1. [Installation](#markdown-header-installation)
1. [Resources](#markdown-header-resources)
    1. [Relationships](#markdown-header-relationships)
1. [Attributes and Meta-Informations](#markdown-header-attributes-and-meta-informations)
1. [Links](#markdown-header-links)
1. [Documents](#markdown-header-documents)
    1. [Document Serializer](#markdown-header-document-serializer)
1. [Exceptions and Errors](#markdown-header-exceptions-and-errors)
    
*****

## Installation

    composer require enm/json-api-common

*****

## Resources
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

### Relationships
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

## Attributes and Meta-Informations
Resources and documents allowing access to attributes and meta informations via `attributes()` and `metaInformations()`,
which will both return an instance of `Enm\JsonApi\Model\Common\KeyValueCollectionInterface`.

The collections will allow you to add, remove or get values by key, getting all key-values-pairs or check for the existence of a value by key.

`Enm\JsonApi\Model\Common\KeyValueCollectionInterface`:

| Method                                                                         | Return Type                 | Description                                                                                                                                                                                                                                                                                                                                                                                                |
|--------------------------------------------------------------------------------|-----------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| all()                                                                          | array                       | All elements as key-value-array.                                                                                                                                                                                                                                                                                                                                                                           |
| count()                                                                        | int                         | Number of collection entries.                                                                                                                                                                                                                                                                                                                                                                              |
| isEmpty()                                                                      | bool                        | Checks if the collection contains any elements.                                                                                                                                                                                                                                                                                                                                                            |
| has(string $key)                                                               | bool                        | Checks if the collection contains a special element.                                                                                                                                                                                                                                                                                                                                                       |
| getRequired(string $key)                                                       | mixed                       | Returns an element or throws an \InvalidArgumentException if element does not exists.                                                                                                                                                                                                                                                                                                                      |
| getOptional(string $key, $defaultValue = null)                                 | mixed                       | Returns an element or the defined default value if element does not exists.                                                                                                                                                                                                                                                                                                                                |
| createSubCollection(string $key, bool $required = true)                        | KeyValueCollectionInterface | Creates a new collection for a collection element. If required and element does not exists, an \InvalidArgumentException will be thrown. If the element exists but is not an array an \InvalidArgumentException will be thrown.  ATTENTION: If you want to store changed value of the sub collection under the parent collections original key you have to call: `$collection->set($key, $subCollection);` |
| merge(array $data, bool $overwrite = true)                                     | $this                       | Merges the given array into the current collection. If overwrite is set to true (default) existing values are overwritten by the new values, otherwise they will be ignored.                                                                                                                                                                                                                               |
| mergeCollection(SimpleCollectionInterface $collection, bool $overwrite = true) | $this                       | Merges the given collection into the current one. If overwrite is set to true (default) existing values are overwritten by the new values, otherwise they will be ignored.                                                                                                                                                                                                                                 |
| set(string $key, $value)                                                       | $this                       | Set a key-value-pair into the collection.                                                                                                                                                                                                                                                                                                                                                                  |
| remove(string $key)                                                            | $this                       | Remove an element by key from the collection.                                                                                                                                                                                                                                                                                                                                                              |

*****

## Links
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

## Documents
Document are the root objects for a request/response.

A document contains "data" (the resources) and can contain meta informations, errors and links.

For standard use cases there is no need to access and modify a document directly, but if needed (for example with pagination), it can be accessed and modified through events.

### Document Serializer
The document serializer (a class implementing `Enm\JsonApi\Serializer\DocumentSerializerInterface`) is responsible for turning
document objects (and their resources) into a php array in json api structure which can be json_encoded and returned as response. 
In nearly every use case the default serializer should be used, but, if needed, it's possible to use a custom serializer. 

*****

## Exceptions and Errors
Exceptions should turned into instances of `Enm\JsonApi\Model\Error\ErrorInterface` to handle it json api like.

The simplest way is to use the default implementation `Enm\JsonApi\Model\Error\Error`, which offers a static method to create an 
error object from an exception.

    Enm\JsonApi\Model\Error\Error::createFromException(new \Exception());

The following Exception are available to be handled including the correct http status code:

* `Enm\JsonApi\Exception\Exception`: For general server errors
* `Enm\JsonApi\Exception\InvalidRequestException`: For client (request) errors
* `Enm\JsonApi\Exception\ResourceNotFoundException`: For 404 errors on a concrete resource
* `Enm\JsonApi\Exception\UnsupportedMediaTypeException`: For invalid content type header
* `Enm\JsonApi\Exception\UnsupportedTypeException`: For 404 errors on a resource list
* `Enm\JsonApi\Exception\NotAllowedException`: For 403 errors
* `Enm\JsonApi\Exception\HttpException`: For custom http status codes
