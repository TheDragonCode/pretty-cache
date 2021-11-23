<?php

declare(strict_types=1);

namespace Tests;

use DragonCode\Cache\Services\Cache;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Tests\Concerns\RefreshCache;

abstract class TestCase extends BaseTestCase
{
    use RefreshCache;

    protected $cache = 'array';

    protected $ttl = 60;

    protected $when;

    protected $tags = ['qwerty', 'cache'];

    protected $keys = ['Foo', 'Bar', 'Baz'];

    protected $value = 'Foo';

    protected function getEnvironmentSetUp($app)
    {
        $this->setConfig($app);
    }

    protected function setConfig($app): void
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $app['config'];

        $config->set('cache.default', $this->cache);
    }

    protected function cache(array $tags = null, $ttl = null): Cache
    {
        $tags = $tags ?: $this->tags;
        $ttl  = $ttl ?: $this->ttl;

        return Cache::make()
            ->when($this->when)
            ->ttl($ttl)
            ->key(...$this->keys)
            ->tags(...$tags);
    }
}
