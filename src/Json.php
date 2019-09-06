<?php

namespace Rawilk\LaravelModules;

use Illuminate\Filesystem\Filesystem;
use Rawilk\LaravelModules\Exceptions\InvalidJson;

class Json
{
    /** @var \Rawilk\LaravelModules\Collection */
    protected $attributes;

    /** @var \Illuminate\Filesystem\Filesystem */
    protected $filesystem;

    /** @var string */
    protected $path;

    public function __construct($path, Filesystem $filesystem = null)
    {
        $this->path = (string) $path;
        $this->filesystem = $filesystem ?: new Filesystem;
        $this->attributes = Collection::make($this->getAttributes());
    }

    public static function make($path, Filesystem $filesystem = null): self
    {
        return new static($path, $filesystem);
    }

    public function get(string $key, $default = null)
    {
        return $this->attributes->get($key, $default);
    }

    public function getAttributes(): array
    {
        $attributes = json_decode($this->getContents(), true);

        if (json_last_error() > 0) {
            throw InvalidJson::invalidString($this->getPath());
        }

        if (! config('modules.cache.enabled')) {
            return $attributes;
        }

        return app('cache')->remember($this->getPath(), config('modules.cache.lifetime'), function () use ($attributes) {
            return $attributes;
        });
    }

    public function getContents(): string
    {
        return $this->filesystem->get($this->getPath());
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getFilesystem(): Filesystem
    {
        return $this->filesystem;
    }

    public function save(): bool
    {
        return $this->filesystem->put($this->getPath(), $this->toJsonPretty());
    }

    public function set(string $key, $value): self
    {
        $this->attributes->offsetSet($key, $value);

        return $this;
    }

    public function setPath($path): self
    {
        $this->path = (string) $path;

        return $this;
    }

    public function setFilesystem(Filesystem $filesystem): self
    {
        $this->filesystem = $filesystem;

        return $this;
    }

    public function toJsonPretty(array $data = null): string
    {
        return json_encode($data ?: $this->attributes, JSON_PRETTY_PRINT);
    }

    public function update(array $data): bool
    {
        $this->attributes = new Collection(array_merge($this->attributes->toArray(), $data));

        return $this->save();
    }

    public function __call($method, $arguments = [])
    {
        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $arguments);
        }

        return call_user_func_array([$this->attributes, $method], $arguments);
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __toString()
    {
        return $this->getContents();
    }
}
