[back to README](../README.md)
# Documents
Document are the root objects for a request/response.

A document contains "data" (the resources) and can contain meta informations, errors and links.

For standard use cases there is no need to access and modify a document directly, but if needed (for example with pagination), it can be accessed and modified through events.

## Document Serializer
The document serializer (a class implementing `Enm\JsonApi\Serializer\DocumentSerializerInterface`) is responsible for turning
document objects (and their resources) into a php array in json api structure which can be json_encoded and returned as response. 
In nearly every use case the default serializer should be used, but, if needed, it's possible to use a custom serializer. 

*****

[prev: Links](../docs/03-links.md) | [back to README](../README.md) | [next: Errors](../docs/05-errors.md)