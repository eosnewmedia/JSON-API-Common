[back to README](../README.md)
# Factories
Resources, relationships and documents should always be created by factories. 
The library offers default implementations for all factories and there is no need to do anything if you don't need custom documents or resources.

`Enm\JsonApi\Model\Factory\DocumentFactoryInterface` (extends `Enm\JsonApi\Model\Factory\ResourceFactoryAwareInterface`):

| Method                                                        | Return type                                                      | Description                                                                                                                                                                                                 |
|---------------------------------------------------------------|------------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| create($data, string $version)                                | [DocumentInterface](../src/Model/Document/DocumentInterface.php) | Create a document object. "data" can be `null` or a resource object for a "single resource document" OR an empty array, a ResourceCollection or an array of resource objects for a "multi resource document". |


A document factory should look like:

```php
class DocumentFactory implements DocumentFactoryInterface
{
    use ResourceFactoryAwareTrait;

    /**
     * @param ResourceInterface|ResourceInterface[]|ResourceCollectionInterface|array|null $data
     * @param string $version
     * @return DocumentInterface
     */
    public function create($data = null, string $version = JsonApiInterface::CURRENT_VERSION): DocumentInterface
    {
        return new Document($data, $version);
    }
}
```

`Enm\JsonApi\Model\Factory\ResourceFactoryInterface`:

| Method                           | Return type                                                      | Description                   |
|----------------------------------|------------------------------------------------------------------|-------------------------------|
| create(string $type, string $id) | [ResourceInterface](../src/Model/Resource/ResourceInterface.php) | Create a new resource object. |


A resource factory should look like:

```php
class ResourceFactory implements ResourceFactoryInterface
{
    /**
     * @param string $type
     * @param string $id
     * @return ResourceInterface
     */
    public function create(string $type, string $id): ResourceInterface
    {
        return new JsonResource($type, $id);
    }
}
```

`Enm\JsonApi\Model\Factory\RelationshipFactoryInterface`:

| Method                           | Return type                                                                           | Description                       |
|----------------------------------|---------------------------------------------------------------------------------------|-----------------------------------|
| create(string $name, $related)   | [RelationshipInterface](../src/Model/Resource/Relationship/RelationshipInterface.php) | Create a new relationship object. |


A relationship factory should look like:

```php
class RelationshipFactory implements RelationshipFactoryInterface
{
    /**
     * @param string $name
     * @param array|ResourceCollectionInterface|ResourceInterface|ResourceInterface[]|null $related
     * @return RelationshipInterface
     */
    public function create(string $name, $related = null): RelationshipInterface
    {
        return new Relationship($name, $related);
    }
}
```

## Resource Factory Registry

If you need to create custom resource objects for different resource types you can use
`Enm\JsonApi\Model\Factory\ResourceFactoryRegistry` as factory and add factories for each resource type.

If a type is requested which does not has an own factory the default factory (can be overridden) will be used.

```php
$registry = new ResourceFactoryRegistry();
$registry->setDefaultFactory(new ResourceFactory()); // optional
$registry->addResourceFactory('customType', new CustomFactory());

$registry->create('customType', 'id'); // will return your custom resource object
$registry->create('normalType', 'id'); // will return a default resource object
```

*****

[prev: Errors](../docs/06-errors.md) | [back to README](../README.md) | [next: Requests](../docs/08-requests.md)
