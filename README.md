# suven/flint-redis

[![Build Status](https://travis-ci.org/Suven/flintRedis.svg?branch=master)](https://travis-ci.org/Suven/flintRedis)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Suven/flintRedis/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Suven/flintRedis/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Suven/flintRedis/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Suven/flintRedis/?branch=master)

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

```php
use Suven\FlintRedis\FlintRedisCacheFactory;

if ($youWantToUseRedis) {
    FlintRedisCacheFactory::$strategy = FlintRedisCacheFactory::STRATEGY_REDIS;
    FlintRedisCacheFactory::$options = [
        "yourOptions" => "that you'd like to pass to predis"
    ];
}

if ($youWantToUseFlintstone) {
    FlintRedisCacheFactory::$strategy = FlintRedisCacheFactory::STRATEGY_FLINTSTONE;
    FlintRedisCacheFactory::$options = [
        "yourOptions" => "that you'd like to pass to flintstone"
    ];
}

// Get a new collection for settings
$settings = FlintRedisCacheFactory::create("settings");

// Set a value
$settings->set('target', 'worldDomination');
$settings->set('favNumber', 42);
$settings->set('someBool', true);
$settings->set('youCanStoreAnythingPHPcanSerialize', [
    'foo' => 'bar'
]);

// Get a value by key
$favNumber = $settings->get('favNumber');

// Get all values
$allSettings = $settings->getAll();

// Get all keys
$settingKeys = $settings->getKeys();

// Delete a value by key
$settings->delete('favNumber');

// Delete all settings
$settings->flush();
```
