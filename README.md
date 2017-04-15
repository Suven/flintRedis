# [WIP] suven/flint-redis

Super-thin and opinionated wrapper around [flintstone](https://github.com/fire015/flintstone) and [predis](https://github.com/nrk/predis).

Enables you to store multiple key-value collections with redis or the filesystem.

## Background

### Why would I want that?

If you are writing apps that need to cache data in collections, you would want to
use some fast key-value-store such as redis or memcached. Unfortunally, redis
is not always available - especially on shared hosters. In those scenarios, if
you still need to cache stuff, the filesystem is often a better choice, then not
to cache.

### Y U NO PSR-6?!

There are plenty ready-to-use caching-adapters out there, which implement some
common interfaces. Those adapters are often well tested, powerful and well
maintained. They also might give you more freedom in choosing more providers.
If you also need to offer support for memcached or db-caching, you should choose
one of those.

That said, the power of this library comes from the fact that it's minimal and
opinionated. It provides you with exactly two options for caching-providers and
comes with all functions, those two providers share.

## Installation

`composer require suven/flint-redis`

## Usage

```
use Suven\FlintRedis\FlintRedisCacheFactory;

// Get a new redis-instance for a collection named settings
$settings = FlintRedisCacheFactory::create("settings", FlintRedisCacheFactory::STRATEGY_REDIS);

// Get a new flintstone-instance for a collection named users
$users = FlintRedisCacheFactory::create("users", FlintRedisCacheFactory::STRATEGY_FLINTSTONE);

// If a collection with given name (and strategy) was already created, that is reused
$theSameUsersAsBefore = FlintRedisCacheFactory::create("users");

// You can also provide options. The options are the same as described in predis/flintstone
$stuff = FlintRedisCacheFactory::create("stuff", FlintRedisCacheFactory::STRATEGY_FLINTSTONE, [
    'foo' => 'bar'
]);

// Get a value by key
$foo = $settings->get('foo');

// Get all values
$allSettings = $settings->getAll();

// Get all keys
$settingKeys = $settings->getKeys();

// Delete a value by key
$settings->delete('foo');

// Delete all settings
$settings->flush();
```
