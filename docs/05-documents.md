[back to README](../README.md)
# Documents
Documents are the root objects for a request body (post, patch) or response body (`Enm\JsonApi\Model\Document\DocumentInterface`).

A document contains "data" (the resources) and can contain meta information, errors, links and related resources ("includes").

## Document Serializer
The document serializer (a class implementing `Enm\JsonApi\Serializer\DocumentSerializerInterface`) is responsible for turning
document objects (and their resources) into a php array in JSON API structure which can be JSON encoded and returned as response. 
The default serializer should be a good choice but it's also possible to use a custom serializer.

The default serializer will remove empty "data" if "errors" or "meta" is available. It will also remove empty data in 
relationship if "meta" or "links" is available for a relationship.
If empty data should be retained, the serializer offers the option `keepEmptyData` as first constructor argument. 

## Document Deserializer
The document deserializer (a class implementing `Enm\JsonApi\Serializer\DocumentDeserializerInterface`) is responsible 
for turning php arrays into document objects (and their resources).
The default deserializer should be a good choice but it's also possible to use a custom deserializer.

*****

[prev: Links](../docs/04-links.md) | [back to README](../README.md) | [next: Errors](../docs/06-errors.md)
