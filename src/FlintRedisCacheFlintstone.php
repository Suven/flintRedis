<?php
namespace Suven\FlintRedis;

use Flintstone\Flintstone;

class FlintRedisCacheFlintstone implements FlintRedisCache
{

    private $flintstone;

    public function __construct($realm = 'default', $options = [])
    {
        $this->flintstone = new Flintstone($realm, $options);
    }

    public function get($key)
    {
        return $this->flintstone->get($key);
    }

    public function getAll()
    {
        return $this->flintstone->getAll();
    }

    public function getKeys()
    {
        return $this->flintstone->getKeys();
    }

    public function set($key, $value)
    {
        return $this->flintstone->set($key, $value);
    }

    public function delete($key)
    {
        return $this->flintstone->delete($key);
    }

    public function flush()
    {
        return $this->flintstone->flush();
    }
}
