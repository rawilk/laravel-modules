<?php

namespace Rawilk\LaravelModules\Support\Config;

class GeneratorPath
{
    /** @var bool */
    private $generate;

    /** @var string */
    private $path;

    /** @var string */
    private $namespace;

    /**
     * @param array|string $config
     */
    public function __construct($config)
    {
        if (is_array($config)) {
            $this->path = $config['path'];
            $this->generate = $config['generate'];
            $this->namespace = $config['namespace'] ?? $config['path'];

            return;
        }

        $this->path = $config;
        $this->generate = (bool) $config;
        $this->namespace = $config;
    }

    public function generate(): bool
    {
        return $this->generate;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
