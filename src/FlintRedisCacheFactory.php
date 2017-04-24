<?php
namespace Suven\FlintRedis;

abstract class FlintRedisCacheFactory
{
    const STRATEGY_REDIS = 1;
    const STRATEGY_FLINTSTONE = 2;

    private static $cachedInstances = [];

    /**
     * @var number Either FlintRedisCacheFactory::STRATEGY_REDIS or FlintRedisCacheFactory:: STRATEGY_FLINTSTONE
     */
    public static $strategy = self::STRATEGY_REDIS;

    /**
     * @var array Options you want to pass to predis/flintstone
     */
    public static $options = [];

    /**
     * Retrieves a new or previously created cache-instance for the provided
     * parameters.
     *
     * @param  string  $realm    Your collections name
     * @return FlintRedisCache
     */
    public static function create($realm, $strategy = false, $options = false)
    {
        $instance = self::retrieveOldInstance($realm);

        if ($strategy) {
            self::$strategy = $strategy;
        }

        if ($options) {
            self::$options = $options;
        }

        if (!$instance) {
            $instance = self::newCacheInstance($realm);
        }

        return $instance;
    }

    private static function retrieveOldInstance($realm)
    {
        return isset(self::$cachedInstances[$realm]) ? self::$cachedInstances[$realm] : false;
    }

    private static function newCacheInstance($realm)
    {
        if (self::$strategy === self::STRATEGY_REDIS) {
            self::$cachedInstances[$realm] = new FlintRedisCacheRedis($realm, self::$options);
        } else {
            self::$cachedInstances[$realm] = new FlintRedisCacheFlintstone($realm, self::$options);
        }

        return self::$cachedInstances[$realm];
    }
}
