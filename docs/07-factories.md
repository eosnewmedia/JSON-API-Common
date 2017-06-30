[back to README](../README.md)
# Factories
Resources and documents should always be created by factories. 
The library offers default implementations for all factories and there is no need to do anything if you don't need custom documents or resources.

`Enm\JsonApi\Model\Factory\ResourceFactoryAwareInterface`:

| Method                                                        | Return Type       | Description                                                                                                                                                                                                 |
|---------------------------------------------------------------|-------------------|-----------------------------------------------------------------------------------------------------------------|
| setResourceFactory(ResourceFactoryInterface $resourceFactory) | void              | Set the resource factory which should be used in documents (for "data" and "included") to create new resources. |

The simplest way to use resource factory aware is to use `Enm\JsonApi\Model\Factory\ResourceFactoryAwareTrait` in your class.

If created through the default implementations of document and resource factory, the resource factory will be injected
into all resource collections, relationship collections and relationships.

`Enm\JsonApi\Model\Factory\DocumentFactoryInterface` (extends `Enm\JsonApi\Model\Factory\ResourceFactoryAwareInterface`):

| Method                                                        | Return Type       | Description                                                                                                                                                                                                 |
|---------------------------------------------------------------|-------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| create($data, string $version)                                | DocumentInterface | Create a document object. "data" can be null or a resource object for a "single resource document" OR an empty array, a ResourceCollection or an array of resource objects for a "multi resource document". |


A document factory should look like:

```php
class DocumentFactory implements DocumentFactoryInterface
{
    use ResourceFactoryAwareTrait;

    /**
     * @param ResourceInterface|ResourceInterface[]|ResourceCollectionInterface|array|null $data
     * @param string $version
     * @return DocumentInterface
     * @throws \InvalidArgumentException
     */
    public function create($data = null, string $version = JsonApiInterface::CURRENT_VERSION): DocumentInterface
    {
        $document = new Document($data, $version);
        $document->data()->setResourceFactory($this->resourceFactory());
        $document->included()->setResourceFactory($this->resourceFactory());

        return $document;
    }
}
```

`Enm\JsonApi\Model\Factory\ResourceFactoryInterface`:

| Method                           | Return Type       | Description                                                                                                                                                     |
|----------------------------------|-------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------|
| create(string $type, string $id) | ResourceInterface | "Create a new resource object. Should inject an instance of resource interface into "resource->relationships()", which implements ResourceFactoryAwareInterface |


A resource factory should look like:

```php
class ResourceFactory implements ResourceFactoryInterface
{
    /**
     * @param string $type
     * @param string $id
     * @return ResourceInterface
     * @throws \InvalidArgumentException
     */
    public function create(string $type, string $id): ResourceInterface
    {
        $resource = new JsonResource($type, $id);
        $resource->relationships()->setResourceFactory($this);

        return $resource;
    }
}
```

*****

[prev: Errors](../docs/06-errors.md) | [back to README](../README.md)
