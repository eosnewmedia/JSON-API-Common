[back to README](../README.md)
# Entry Point
If your json api entry point implements `Enm\JsonApi\JsonApiInterface` you have access to these methods:

| Method                                                             | Return Type           | Description                                            |
|--------------------------------------------------------------------|-----------------------|--------------------------------------------------------|
| resource(string $type, string $id)                                 | ResourceInterface     | Create a new json resource                             |
| singleResourceDocument(ResourceInterface $resource = null)         | DocumentInterface     | Create a document which can contain one resource       |
| multiResourceDocument(array $resource = [])                        | DocumentInterface     | Create a document which can contain multiple resources |
| serializeDocument(DocumentInterface $document)                     | array                 | Create the array representation of a document          |
| deserializeDocument(array $document)                               | DocumentInterface     | Create a document object from a given array            |

The simplest way of implementing the interface is to use the `Enm\JsonApi\JsonApiTrait`, which offers you the possibility
to configure the way of creating resources, documents and collection by setting your custom factories for usage:

| Method                                                                       | Return Type | Description                             |
|------------------------------------------------------------------------------|-------------|-----------------------------------------|
| setDocumentFactory(DocumentFactoryInterface $documentFactory)                | void        | Set a non default document factory      |
| setResourceFactory(ResourceFactoryInterface $resourceFactory)                | void        | Set a non default resource factory      |
| setDocumentSerializer(DocumentSerializerInterface $documentSerializer)       | void        | Set a non default document serializer   |
| setDocumentDeserializer(DocumentDeserializerInterface $documentDeserializer) | void        | Set a non default document deserializer |

*****

[back to README](../README.md) | [next: Resources](../docs/02-resources.md)
