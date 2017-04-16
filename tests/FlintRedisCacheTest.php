<?php
namespace Suven\FlintRedis\Test;

use Suven\FlintRedis\FlintRedisCacheFactory;
use PHPUnit\Framework\TestCase;

class FlintRedisCacheTest extends TestCase
{
    /**
     * @dataProvider cacheProvider
     */
    public function testSet($collection)
    {
        $collection->set('testString', 'string');
        $collection->set('testNumber', 4711);
        $collection->set('testAssocArray', [ 'foo' => 'bar' ]);
        $collection->set('testIndexArray', [ 123, 456 ]);
    }

    /**
     * @depends testSet
     * @dataProvider cacheProvider
     */
    public function testGet($collection)
    {
        $this->assertFalse($collection->get('unknownKey'), "Non-existing keys return false");
        $this->assertEquals($collection->get('testString'), 'string');
        $this->assertEquals($collection->get('testNumber'), 4711);
        $this->assertEquals($collection->get('testAssocArray'), [ 'foo' => 'bar' ]);
        $this->assertEquals($collection->get('testIndexArray'), [ 123, 456 ]);
    }

    /**
     * @depends testSet
     * @dataProvider cacheProvider
     */
    public function testGetAll($collection)
    {
        $this->assertEquals($collection->getAll(), [
            'testString' => 'string',
            'testNumber' => 4711,
            'testAssocArray' => [ 'foo' => 'bar' ],
            'testIndexArray' => [ 123, 456 ]
        ]);
    }

    /**
     * @depends testSet
     * @dataProvider cacheProvider
     */
    public function testGetKeys($collection)
    {
        $this->assertEquals($collection->getKeys(), [
            'testString', 'testNumber', 'testAssocArray', 'testIndexArray'
        ]);
    }

    /**
     * @depends testSet
     * @dataProvider cacheProvider
     */
    public function testDelete($collection)
    {
        $collection->set('tempTest', 999);
        $this->assertEquals($collection->get('tempTest'), 999);
        $collection->delete('tempTest');
        $this->assertFalse($collection->get('tempTest'));
    }

    /**
     * @dataProvider flushProvider
     */
    public function testFlush($collection)
    {
        $collection->set('tempTestFoo', 999);
        $this->assertGreaterThan(1, sizeof($collection->getKeys()));
        $collection->flush();
        $this->assertEquals(0, sizeof($collection->getKeys()));
    }

    public function cacheProvider()
    {
        return [
            'flintstone' => [ FlintRedisCacheFactory::create('testGet', FlintRedisCacheFactory::STRATEGY_FLINTSTONE) ],
            // 'redis' => [ FlintRedisCacheFactory::create('testGet', FlintRedisCacheFactory::STRATEGY_REDIS) ]
        ];
    }

    public function flushProvider()
    {
        return [
            'flintstone' => [ FlintRedisCacheFactory::create('testGet', FlintRedisCacheFactory::STRATEGY_FLINTSTONE) ],
            // 'redis' => [ FlintRedisCacheFactory::create('testGet', FlintRedisCacheFactory::STRATEGY_REDIS) ]
        ];
    }
}
