<?php

namespace Rawilk\LaravelModules;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection as BaseCollection;

class Collection extends BaseCollection
{
    public function getItems(): array
    {
        return $this->items;
    }

    public function toArray(): array
    {
        return array_map(static function ($value) {
            if ($value instanceof Module) {
                $attributes = $value->json()->getAttributes();
                $attributes['path'] = $value->getPath();

                return $attributes;
            }

            return $value instanceof Arrayable ? $value->toArray() : $value;
        }, $this->items);
    }
}
