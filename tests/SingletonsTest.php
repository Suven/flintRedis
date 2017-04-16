<?php
namespace Suven\FlintRedis\Test;

use Suven\FlintRedis\FlintRedisCacheFactory;
use PHPUnit\Framework\TestCase;

// @codingStandardsIgnoreStart
class SingletonsTest extends TestCase
{

    public function testLazylyGetSameInstance()
    {
        // All the same
        $instanceA1 = FlintRedisCacheFactory::create("testLazylyGetSameInstance-a", FlintRedisCacheFactory::STRATEGY_REDIS, [ 'foo' => 'bar' ]);
        $instanceA2 = FlintRedisCacheFactory::create("testLazylyGetSameInstance-a", FlintRedisCacheFactory::STRATEGY_REDIS, [ 'foo' => 'bar' ]);
        $instanceA3 = FlintRedisCacheFactory::create("testLazylyGetSameInstance-a", FlintRedisCacheFactory::STRATEGY_REDIS);
        $instanceA4 = FlintRedisCacheFactory::create("testLazylyGetSameInstance-a");
        // Different ones
        $instanceA5 = FlintRedisCacheFactory::create("testLazylyGetSameInstance-a", FlintRedisCacheFactory::STRATEGY_FLINTSTONE, [ 'foo' => 'bar' ]);
        $instanceA6 = FlintRedisCacheFactory::create("testLazylyGetSameInstance-a", FlintRedisCacheFactory::STRATEGY_REDIS, [ 'bar' => 'bar' ]);

        $this->assertSame($instanceA1, $instanceA2);
        $this->assertSame($instanceA1, $instanceA3);
        $this->assertSame($instanceA1, $instanceA4);
        $this->assertNotSame($instanceA1, $instanceA5);
        $this->assertNotSame($instanceA1, $instanceA6);
    }

    public function testDifferentRealms()
    {
        $instanceA = FlintRedisCacheFactory::create("testDifferentRealms-a");
        $instanceB = FlintRedisCacheFactory::create("testDifferentRealms-b");
        $anotherA = FlintRedisCacheFactory::create("testDifferentRealms-a");
        $anotherB = FlintRedisCacheFactory::create("testDifferentRealms-b");

        $this->assertSame($instanceA, $anotherA, "The same realm, returns the same instance");
        $this->assertSame($instanceB, $anotherB, "The same realm, returns the same instance");
        $this->assertNotSame($instanceA, $instanceB, "Different realms, provide different instances");
    }

    public function testDifferentStrategies()
    {
        $instanceARedis = FlintRedisCacheFactory::create("testDifferentStrategies-a", FlintRedisCacheFactory::STRATEGY_REDIS);
        $instanceBRedis = FlintRedisCacheFactory::create("testDifferentStrategies-b", FlintRedisCacheFactory::STRATEGY_REDIS);
        $instanceAFlintstone = FlintRedisCacheFactory::create("testDifferentStrategies-a", FlintRedisCacheFactory::STRATEGY_FLINTSTONE);
        $instanceBFlintstone = FlintRedisCacheFactory::create("testDifferentStrategies-b", FlintRedisCacheFactory::STRATEGY_FLINTSTONE);


        $this->assertNotSame($instanceARedis, $instanceBRedis, "Different realms, provide different instances");
        $this->assertNotSame($instanceAFlintstone, $instanceBFlintstone, "Different realms, provide different instances");
        $this->assertNotSame($instanceARedis, $instanceAFlintstone, "Different strategys, provide different instances");
    }

    public function testDifferentOptions()
    {
        $instanceA1 = FlintRedisCacheFactory::create("testDifferentOptions-a", FlintRedisCacheFactory::STRATEGY_REDIS, [ 'foo' => 'bar' ]);
        $instanceA2 = FlintRedisCacheFactory::create("testDifferentOptions-a", FlintRedisCacheFactory::STRATEGY_REDIS, [ 'bar' => 'foo' ]);
        $instanceB1 = FlintRedisCacheFactory::create("testDifferentOptions-b", FlintRedisCacheFactory::STRATEGY_FLINTSTONE, [ 'foo' => 'bar' ]);
        $instanceB2 = FlintRedisCacheFactory::create("testDifferentOptions-b", FlintRedisCacheFactory::STRATEGY_FLINTSTONE, [ 'bar' => 'foo' ]);

        $this->assertNotSame($instanceA1, $instanceA2, "Different options, provide different instances");
        $this->assertNotSame($instanceB1, $instanceB2, "Different options, provide different instances");
    }

    public function testNoOptionFallback()
    {
        $instanceA1 = FlintRedisCacheFactory::create("testNoOptionFallback-a", FlintRedisCacheFactory::STRATEGY_REDIS, [ 'foo' => 'bar' ]);
        $instanceA2 = FlintRedisCacheFactory::create("testNoOptionFallback-a", FlintRedisCacheFactory::STRATEGY_REDIS);
        $instanceA3 = FlintRedisCacheFactory::create("testNoOptionFallback-a");
        $instanceB1 = FlintRedisCacheFactory::create("testNoOptionFallback-b", FlintRedisCacheFactory::STRATEGY_FLINTSTONE, [ 'foo' => 'bar' ]);
        $instanceB2 = FlintRedisCacheFactory::create("testNoOptionFallback-b", FlintRedisCacheFactory::STRATEGY_FLINTSTONE);
        $instanceB3 = FlintRedisCacheFactory::create("testNoOptionFallback-b");

        $this->assertSame($instanceA1, $instanceA2, "Providing no options, returns a previous instance");
        $this->assertSame($instanceA1, $instanceA3, "Providing no options, returns a previous instance");
        $this->assertSame($instanceB1, $instanceB2, "Providing no options, returns a previous instance");
        $this->assertSame($instanceB1, $instanceB3, "Providing no options, returns a previous instance");
        $this->assertNotSame($instanceA1, $instanceB1, "Different realms, provide different instances");
    }
}
