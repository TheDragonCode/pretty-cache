<?php

declare(strict_types=1);

namespace DragonCode\Cache\Support;

use DragonCode\Support\Facades\Helpers\Ables\Arrayable;
use DragonCode\Support\Facades\Helpers\Ables\Stringable;

class Tag
{
    public function get(array $tags): array
    {
        return Arrayable::of($tags)
            ->flatten()
            ->map(function (string $tag) {
                return $this->slug($tag);
            })->get();
    }

    protected function slug(string $tag): string
    {
        return (string) Stringable::of($tag)->trim()->slug();
    }
}
