<?php
namespace Suven\FlintRedis;

abstract class FlintRedisCacheFactory
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

    /**
     * Retrieves a new or previously created cache-instance for the provided
     * parameters.
     *
     * If you previously created an instance for a collection 'foo' with a
     * strategy [and options] and are later 'creating' in instance but only
     * provide the realm 'foo', you will retreive your previously created instance.
     *
     * That way you only have to provide the strategy and options once per $realm.
     *
     * @param  string  $realm    Your collections name
     * @param  number  $strategy Either FlintRedisCacheFactory::STRATEGY_REDIS
     *                           or FlintRedisCacheFactory:: STRATEGY_FLINTSTONE
     * @param  array   $options  Options you want to pass to predis/flintstone
     * @return FlintRedisCache
     */
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
            return new FlintRedisCacheRedis($realm, $options);
        }

        if ($strategy === self::STRATEGY_FLINTSTONE) {
            return new FlintRedisCacheFlintstone($realm, $options);
        }
    }
}
