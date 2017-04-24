<?php
namespace Suven\FlintRedis\Test;

use Suven\FlintRedis\FlintRedisCacheFactory;
use PHPUnit\Framework\TestCase;

class FlintRedisCacheFactoryTest extends TestCase
{

    public function testSameRealms()
    {
        FlintRedisCacheFactory::$strategy = FlintRedisCacheFactory::STRATEGY_REDIS;
        $instanceA1 = FlintRedisCacheFactory::create("testSameRealms-a");
        $instanceA2 = FlintRedisCacheFactory::create("testSameRealms-a");

        FlintRedisCacheFactory::$strategy = FlintRedisCacheFactory::STRATEGY_FLINTSTONE;
        $instanceB1 = FlintRedisCacheFactory::create("testSameRealms-b");
        $instanceB2 = FlintRedisCacheFactory::create("testSameRealms-b");

        $this->assertSame($instanceA1, $instanceA2);
        $this->assertSame($instanceB1, $instanceB2);
    }

    public function testDifferentRealms()
    {
        FlintRedisCacheFactory::$strategy = FlintRedisCacheFactory::STRATEGY_REDIS;
        $instanceA = FlintRedisCacheFactory::create("testDifferentRealms-a");
        $instanceB = FlintRedisCacheFactory::create("testDifferentRealms-b");

        FlintRedisCacheFactory::$strategy = FlintRedisCacheFactory::STRATEGY_FLINTSTONE;
        $instanceC = FlintRedisCacheFactory::create("testDifferentRealms-c");
        $instanceD = FlintRedisCacheFactory::create("testDifferentRealms-d");

        $this->assertNotSame($instanceA, $instanceB);
        $this->assertNotSame($instanceB, $instanceC);
        $this->assertNotSame($instanceC, $instanceD);
    }
}
