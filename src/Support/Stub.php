<?php

namespace Rawilk\LaravelModules\Support;

class Stub
{
    /** @var null|string */
    protected static $basePath;

    /** @var string */
    protected $path;

    /** @var array */
    protected $replaces = [];

    /**
     * @param string $path
     * @param array $replaces
     */
    public function __construct(string $path, array $replaces = [])
    {
        $this->path = $path;
        $this->replaces = $replaces;
    }

    public static function create(string $path, array $replaces = []): self
    {
        return new static($path, $replaces);
    }

    public static function getBasePath(): ?string
    {
        return static::$basePath;
    }

    public static function setBasePath(string $path): void
    {
        static::$basePath = $path;
    }

    public function getContents(): string
    {
        $contents = file_get_contents($this->getPath());

        foreach ($this->replaces as $key => $value) {
            $contents = str_replace('$' . strtoupper($key) . '$', $value, $contents);
        }

        return $contents;
    }

    public function getReplaces(): array
    {
        return $this->replaces;
    }

    public function getPath(): string
    {
        $path = static::getBasePath() . $this->path;

        return file_exists($path) ? $path : __DIR__ . '/../Commands/stubs' . $this->path;
    }

    public function render(): string
    {
        return $this->getContents();
    }

    public function replace(array $replaces = []): self
    {
        $this->replaces = $replaces;

        return $this;
    }

    public function saveTo(string $path, string $filename): bool
    {
        return file_put_contents($path . '/' . $filename, $this->getContents());
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function __toString()
    {
        return $this->render();
    }
}
