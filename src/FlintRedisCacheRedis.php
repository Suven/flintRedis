<?php
namespace Suven\FlintRedis;

use Predis\Client;

class FlintRedisCacheRedis implements FlintRedisCache
{

    private $realm;

    private $predis;

    public function __construct($realm = 'default', $options = [])
    {
        $this->realm = $realm;
        $this->predis = new Client($options);
    }

    public function get($key)
    {
        return $this->predis->hget($this->realm, $key);
    }

    public function getAll()
    {
        return $this->predis->hgetall($this->realm);
    }

    public function getKeys()
    {
        return $this->predis->hkeys($this->realm);
    }

    public function set($key, $value)
    {
        return $this->predis->hser($this->realm, $key, $value);
    }

    public function delete($key)
    {
        return $this->predis->hdel($this->realm, $key);
    }

    public function flush()
    {
        return $this->predis->hdel($this->realm, $this->getKeys());
    }
}
