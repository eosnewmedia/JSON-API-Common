JSON API Common
===============
[![Build Status](https://travis-ci.org/eosnewmedia/JSON-API-Common.svg?branch=master)](https://travis-ci.org/eosnewmedia/JSON-API-Common)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/a1696b25-a508-44ec-a39f-4c7180db2c46/mini.png)](https://insight.sensiolabs.com/projects/a1696b25-a508-44ec-a39f-4c7180db2c46)

This library contains php classes and interfaces shared between 
[`enm/json-api-server`](https://eosnewmedia.github.io/JSON-API-Server/) and 
[`enm/json-api-client`](https://github.com/eosnewmedia/JSON-API-Client).

## Installation

```sh
composer require enm/json-api-common
```

*****

## Documentation
1. [EntryPoint](docs/01-entry.md)
1. [Resources](docs/02-resources.md)
    1. [Relationships](docs/02-resources.md#relationships)
1. [Attributes and Meta-Informations](docs/03-collections.md)
1. [Links](docs/04-links.md)
1. [Documents](docs/05-documents.md)
    1. [Document Serializer](docs/05-documents.md#document-serializer)
1. [Exceptions and Errors](docs/06-errors.md)
1. [Factories](docs/07-factories.md)

*****

## Changelog

### Version 2.0.0
#### Model changes:
1. renamed all occurrences of method "metaInformations" to "metaInformation"
1. renamed method ```ResourceInterface::getType()``` to ```ResourceInterface::type()```
1. renamed method ```ResourceInterface::getId()``` to ```ResourceInterface::id()```
1. renamed method ```RelationshipInterface::getType()``` to ```RelationshipInterface::type()```
1. renamed method ```RelationshipInterface::getName()``` to ```RelationshipInterface::name()```
1. renamed method ```LinkInterface::getType()``` to ```LinkInterface::type()```
1. renamed method ```LinkInterface::getName()``` to ```LinkInterface::name()```
1. renamed method ```ErrorInterface::getStatus()``` to ```LinkInterface::status()```
1. renamed method ```ErrorInterface::getCode()``` to ```LinkInterface::code()```
1. renamed method ```ErrorInterface::getTitle()``` to ```LinkInterface::title()```
1. renamed method ```ErrorInterface::getDetail()``` to ```LinkInterface::detail()```
1. added method "createLink" to ```LinkCollectionInterface```
1. removed method "getType", which was only relevant for serializer, from ```DocumentInterface```
1. added method "httpStatus" to ```DocumentInterface```
1. added method "withHttpStatus" to ```DocumentInterface```, to modify the status of a document
1. added method "shouldBeHandledAsCollection", which indicates if a document contains a collection or not, to ```DocumentInterface```
1. added method "jsonApi", which contains information about the version, to ```DocumentInterface```
1. added model class ```Document``` as default implementation of  ```DocumentInterface```
1. removed class ```AbstractDocument``` (use ```Document``` instead)
1. removed class ```ResourceDocument``` (use ```Document``` instead)
1. removed class ```ResourceCollectionDocument``` (use ```Document``` instead)
1. removed class ```RelationshipDocument``` (use ```Document``` instead)
1. removed class ```RelationshipCollectionDocument``` (use ```Document``` instead)
1. removed class ```ErrorDocument``` (use ```Document``` instead)
1. added interface ```JsonApiInterface``` for information about the used version in documents
1. added model class ```JsonApi``` as default implementation of ```JsonApiInterface``` for information about the used version in documents

#### Serializer changes:
1. added argument "identifiersOnly" as second argument of ```DocumentSerializerInterface::serializeDocument```
1. added the key "jsonapi" to output of ```Serializer::serializeDocument```
1. added interface ```DocumentDeserializerInterface```
1. added class ```Deserializer``` as default implementation of ```DocumentDeserializerInterface```

#### new in 2.0.0
1. added ```JsonApiInterface``` and ```JsonApiTrait```
1. added interface ```DocumentFactoryInterface``` and class ```DocumentFactory```
1. added trait ```DocumentFactoryAwareTrait```
1. added interface ```ResourceFactoryInterface``` and class ```ResourceFactory```
1. added trait ```ResourceFactoryAwareTrait```
1. added class ```ResourceFactoryRegistry```
1. added interface ```RelationshipFactoryInterface``` and class ```RelationshipFactory```
1. added trait ```RelationshipFactoryAwareTrait```
