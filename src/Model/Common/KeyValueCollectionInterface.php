<?php
declare(strict_types=1);

namespace Enm\JsonApi\Model\Common;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface KeyValueCollectionInterface extends CollectionInterface
{
    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * @param string $key
     *
     * @return mixed
     * @throws \InvalidArgumentException if key does not exists
     */
    public function getRequired(string $key);

    /**
     * @param string $key
     * @param mixed $defaultValue
     *
     * @return mixed
     */
    public function getOptional(string $key, $defaultValue = null);

    /**
     * Creates a (sub) collection for an array value from the current collection.
     *
     * @param string $key
     * @param bool $required
     * @return KeyValueCollectionInterface
     */
    public function createSubCollection(string $key, bool $required = true): KeyValueCollectionInterface;

    /**
     * @param array $data
     * @param bool $overwrite
     * @return KeyValueCollectionInterface
     */
    public function merge(array $data, bool $overwrite = true): KeyValueCollectionInterface;

    /**
     * @param KeyValueCollectionInterface $collection
     * @param bool $overwrite
     * @return KeyValueCollectionInterface
     */
    public function mergeCollection(
        KeyValueCollectionInterface $collection,
        bool $overwrite = true
    ): KeyValueCollectionInterface;

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return KeyValueCollectionInterface
     */
    public function set(string $key, $value): KeyValueCollectionInterface;

    /**
     * @param string $key
     *
     * @return KeyValueCollectionInterface
     */
    public function remove(string $key): KeyValueCollectionInterface;
}
