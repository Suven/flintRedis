<?php
namespace Suven\FlintRedis\Test;

use Suven\FlintRedis\FlintRedisCacheFactory;

class SingletonsTest extends \PHPUnit_Framework_TestCase
{

    public function testDifferentRealms()
    {
        $instanceA = FlintRedisCacheFactory::create("a");
        $instanceB = FlintRedisCacheFactory::create("b");
        $anotherA = FlintRedisCacheFactory::create("a");
        $anotherB = FlintRedisCacheFactory::create("b");

        $this->assertSame($instanceA, $anotherA, "The same realm, returns the same instance");
        $this->assertSame($instanceB, $anotherB, "The same realm, returns the same instance");
        $this->assertNotSame($instanceA, $instanceB, "Different realms, provide different instances");
    }

    public function testDifferentStrategies()
    {
        $instanceARedis = FlintRedisCacheFactory::create("a", FlintRedisCacheFactory::STRATEGY_REDIS);
        $instanceBRedis = FlintRedisCacheFactory::create("b", FlintRedisCacheFactory::STRATEGY_REDIS);
        $instanceAFlintstone = FlintRedisCacheFactory::create("a", FlintRedisCacheFactory::STRATEGY_FLINTSTONE);
        $instanceBFlintstone = FlintRedisCacheFactory::create("b", FlintRedisCacheFactory::STRATEGY_FLINTSTONE);


        $this->assertNotSame($instanceARedis, $instanceBRedis, "Different realms, provide different instances");
        $this->assertNotSame($instanceAFlintstone, $instanceBFlintstone, "Different realms, provide different instances");
        $this->assertNotSame($instanceARedis, $instanceAFlintstone, "Different strategys, provide different instances");
    }

    public function testDifferentOptions()
    {
        $instanceA1 = FlintRedisCacheFactory::create("a", FlintRedisCacheFactory::STRATEGY_REDIS, [ 'foo' => 'bar' ]);
        $instanceA2 = FlintRedisCacheFactory::create("a", FlintRedisCacheFactory::STRATEGY_REDIS, [ 'bar' => 'foo' ]);
        $instanceB1 = FlintRedisCacheFactory::create("b", FlintRedisCacheFactory::STRATEGY_FLINTSTONE, [ 'foo' => 'bar' ]);
        $instanceB2 = FlintRedisCacheFactory::create("b", FlintRedisCacheFactory::STRATEGY_FLINTSTONE, [ 'bar' => 'foo' ]);

        $this->assertNotSame($instanceA1, $instanceA2, "Different options, provide different instances");
        $this->assertNotSame($instanceB1, $instanceB2, "Different options, provide different instances");
    }

    public function testNoOptionFallback() {
        $instanceA1 = FlintRedisCacheFactory::create("a", FlintRedisCacheFactory::STRATEGY_REDIS, [ 'foo' => 'bar' ]);
        $instanceA2 = FlintRedisCacheFactory::create("a", FlintRedisCacheFactory::STRATEGY_REDIS);
        $instanceB1 = FlintRedisCacheFactory::create("b", FlintRedisCacheFactory::STRATEGY_REDIS, [ 'foo' => 'bar' ]);
        $instanceB2 = FlintRedisCacheFactory::create("b", FlintRedisCacheFactory::STRATEGY_REDIS);

        $this->assertSame($instanceA1, $instanceA2, "Providing no options, returns a previous instance");
        $this->assertSame($instanceB1, $instanceB2, "Providing no options, returns a previous instance");
        $this->assertNotSame($instanceA1, $instanceB1,  "Different realms, provide different instances");
    }

}
