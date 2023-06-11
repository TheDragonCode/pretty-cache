<?php

declare(strict_types=1);

namespace DragonCode\Cache\Support;

use DragonCode\Cache\Services\Storages\Disabled;
use DragonCode\Cache\Services\Storages\MainStore;
use DragonCode\Cache\Services\Storages\TaggedStore;
use DragonCode\Contracts\Cache\Store;
use DragonCode\Support\Concerns\Makeable;
use Illuminate\Support\Facades\Cache;

/**
 * @method static CacheManager make(bool $when = true)
 */
class CacheManager implements Store
{
    use Makeable;

    protected array $tags = [];

    public function __construct(
        protected bool $when = true
    ) {}

    public function tags(array $tags): CacheManager
    {
        $this->tags = $tags;

        return $this;
    }

    public function get(string $key, $default = null): mixed
    {
        return $this->instance()->get($key, $default);
    }

    public function put(string $key, $value, int $seconds): mixed
    {
        return $this->instance()->put($key, $value, $seconds);
    }

    public function remember(string $key, $value, int $seconds): mixed
    {
        return $this->instance()->remember($key, $value, $seconds);
    }

    public function forget(string $key): void
    {
        $this->instance()->forget($key);
    }

    public function has(string $key): bool
    {
        return $this->instance()->has($key);
    }

    public function doesntHave(string $key): bool
    {
        return ! $this->has($key);
    }

    protected function instance(): Store
    {
        return match (true) {
            $this->isDisabled() => Disabled::make(),
            $this->allowTags()  => TaggedStore::make()->tags($this->tags),
            default             => MainStore::make(),
        };
    }

    protected function isDisabled(): bool
    {
        return ! $this->when;
    }

    protected function allowTags(): bool
    {
        return ! empty($this->tags) && method_exists(Cache::getStore(), 'tags');
    }
}
