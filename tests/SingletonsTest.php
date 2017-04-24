<?php
namespace Suven\FlintRedis\Test;

use Suven\FlintRedis\FlintRedisCacheFactory;
use PHPUnit\Framework\TestCase;

class SingletonsTest extends TestCase
{

    public function testSameRealms()
    {
        $instanceA1 = FlintRedisCacheFactory::create("testSameRealms-a");
        $instanceA2 = FlintRedisCacheFactory::create("testSameRealms-a");

        $this->assertSame($instanceA1, $instanceA2);
    }

    public function testDifferentRealms()
    {
        $instanceA = FlintRedisCacheFactory::create("testDifferentRealms-a");
        $instanceB = FlintRedisCacheFactory::create("testDifferentRealms-b");

        $this->assertNotSame($instanceA, $instanceB);
    }
}
