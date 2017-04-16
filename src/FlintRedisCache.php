<?php
namespace Suven\FlintRedis;

interface FlintRedisCache
{
    public function __construct($realm = 'default', $options = []);
    public function get($key);
    public function getAll();
    public function getKeys();
    public function set($key, $value);
    public function delete($key);
    public function flush();
}
