<?php
namespace Suven\FlintRedis;

interface FlintRedisCache
{
    public function __construct($realm = 'default', $options = []);

    /**
     * Returns the value stored for the given key, or false if it did not exist
     * yet.
     *
     * @param  string $key
     * @return mixed  your stored value
     */
    public function get($key);

    /**
     * Returns an associative array with all stored values, in the current
     * collection.
     *
     * @return array
     */
    public function getAll();

    /**
     * Returns a list of all stored keys in the current collection.
     *
     * @return array
     */
    public function getKeys();

    /**
     * Sets/Overwrites the value for the given key.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value);

    /**
     * Unsets the value for given key.
     *
     * @param string $key
     */
    public function delete($key);

    /**
     * Removes all stored values for the current collection.
     */
    public function flush();
}
