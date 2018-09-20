# Changelog

## Version 3.0.0


## Version 2.4.0
1. allow relative url's in links

## Version 2.3.0
1. added `SaveRequest` to save multiple resources at one when manipulating relationships

## Version 2.2.0
1. added method `source` to `ErrorInterface` and use it in `Serializer`

## Version 2.1.0
1. added `RelatedMetaInformationInterface`
1. added `RelatedMetaInformationTrait`
1. improved `Serializer` to ignore empty data if links or meta are provided by a relationship
1. improved `Serializer` to write meta information into resource identifier objects
1. added option "keepEmptyData" to `Serializer`

## Version 2.0.0
### Model changes:
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
1. added interface ```DocumentFactoryInterface``` and class ```DocumentFactory```
1. added interface ```ResourceFactoryInterface``` and class ```ResourceFactory```
1. added class ```ResourceFactoryRegistry```
1. added interface ```RelationshipFactoryInterface``` and class ```RelationshipFactory```
1. added interface `FetchRequestInterface`
1. added class `FetchRequest`
1. added interface `JsonApiRequestInterface`
1. added class `JsonApiRequest`
1. added interface `SaveRequestInterface`
1. added class `RelationshipModificationRequest`
1. added class `SaveSingleResourceRequest`

### Serializer changes:
1. added argument "identifiersOnly" as second argument of ```DocumentSerializerInterface::serializeDocument```
1. added the key "jsonapi" to output of ```Serializer::serializeDocument```
1. added interface ```DocumentDeserializerInterface```
1. added class ```Deserializer``` as default implementation of ```DocumentDeserializerInterface```

### new in 2.0.0
1. added ```JsonApiInterface``` and ```JsonApiTrait```
1. added interface ```JsonApiAwareInterface``` and trait ```JsonApiAwareTrait```
