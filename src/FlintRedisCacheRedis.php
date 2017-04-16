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
        return unserialize($this->predis->hget($this->realm, $key));
    }

    public function getAll()
    {
        $values = [];

        foreach ($this->predis->hgetall($this->realm) as $k => $v) {
            $values[$k] = unserialize($v);
        }

        return $values;
    }

    public function getKeys()
    {
        return $this->predis->hkeys($this->realm);
    }

    public function set($key, $value)
    {
        return $this->predis->hset($this->realm, $key, serialize($value));
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
