[back to README](../README.md)
# Documents
Document are the root objects for a request/response ()`Enm\JsonApi\Model\Document\DocumentInterface`).

A document contains "data" (the resources) and can contain meta information, errors and links.

## Document Serializer
The document serializer (a class implementing `Enm\JsonApi\Serializer\DocumentSerializerInterface`) is responsible for turning
document objects (and their resources) into a php array in json api structure which can be json_encoded and returned as response. 
The default serializer should be a good choice but it's also possible to use a custom serializer.

## Document Deserializer
The document deserializer (a class implementing `Enm\JsonApi\Serializer\DocumentDeserializerInterface`) is responsible 
for turning php arrays into document objects (and their resources).
The default deserializer should be a good choice but it's also possible to use a custom deserializer.

*****

[prev: Links](../docs/04-links.md) | [back to README](../README.md) | [next: Errors](../docs/06-errors.md)
