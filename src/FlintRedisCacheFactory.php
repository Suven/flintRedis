<?php
namespace Suven\FlintRedis;

class FlintRedisCacheFactory
{
    const STRATEGY_REDIS = 1;
    const STRATEGY_FLINTSTONE = 2;

    private static $cachedInstances = [];

    public static function create($realm, $strategy = self::STRATEGY_REDIS, $options = [])
    {
        $key = "${strategy}.${realm}";
        $instance = false;

        if (!empty($options)) {
            $optionsKey = md5(json_encode($options));
            $fullKey = "${key}.${optionsKey}";

            if (!isset(self::$cachedInstances[$fullKey])) {
                $instance = self::newCacheInstance($strategy, $realm, $options);
                self::$cachedInstances[$fullKey] = &$instance;
                self::$cachedInstances[$key] = &$instance;
            }

            return self::$cachedInstances[$fullKey];
        }

        if (!isset(self::$cachedInstances[$key])) {
            self::$cachedInstances[$key] = self::newCacheInstance($strategy, $realm, $options);
        }

        return self::$cachedInstances[$key];
    }

    private static function newCacheInstance($strategy, $realm, $options) {
        if ($strategy === self::STRATEGY_REDIS) {
            return new FlintRedisCacheRedis($strategy, $realm, $options);
        }

        if ($strategy === self::STRATEGY_FLINTSTONE) {
            return new FlintRedisCacheRedis($strategy, $realm, $options);
        }
    }

}
