<?php
namespace Suven\FlintRedis;

class FlintRedisCacheFactory
{
    const STRATEGY_REDIS = 1;
    const STRATEGY_FLINTSTONE = 2;

    private static $cachedInstances = [];

    private static function getKey($realm, $strategy)
    {
        return "${realm}.${strategy}";
    }

    private static function getFullKey($key, $options)
    {
        $optionsKey = md5(json_encode($options));
        return "${key}.${optionsKey}";
    }

    public static function create($realm, $strategy = false, $options = false)
    {
        $key = self::getKey($realm, $strategy);
        $fullKey = self::getFullKey($key, $options);

        if ($strategy && $options && isset(self::$cachedInstances[$fullKey])) {
            return self::$cachedInstances[$fullKey];
        }

        if ($strategy && !$options && isset(self::$cachedInstances[$key])) {
            return self::$cachedInstances[$key];
        }

        if (!$strategy && !$options && isset(self::$cachedInstances[$realm])) {
            return self::$cachedInstances[$realm];
        }

        // No instance available so far... Lets create one with default values
        $strategy = $strategy ? $strategy : self::STRATEGY_REDIS;
        $options = $options ? $options : [];

        $key = self::getKey($realm, $strategy);
        $fullKey = self::getFullKey($key, $options);

        $instance = self::newCacheInstance($strategy, $realm, $options);

        self::$cachedInstances[$fullKey] = &$instance;
        self::$cachedInstances[$key] = &$instance;
        self::$cachedInstances[$realm] = &$instance;

        return $instance;
    }

    private static function newCacheInstance($strategy, $realm, $options)
    {
        if ($strategy === self::STRATEGY_REDIS) {
            return new FlintRedisCacheRedis($strategy, $realm, $options);
        }

        if ($strategy === self::STRATEGY_FLINTSTONE) {
            return new FlintRedisCacheRedis($strategy, $realm, $options);
        }
    }
}
