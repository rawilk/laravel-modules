<?php

namespace Rawilk\LaravelModules\Support\Config;

class GeneratorPath
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var bool
     */
    private $generate;

    /**
     * Create a new class instance.
     *
     * @param array|string $config
     */
    public function __construct($config)
    {
        if (is_array($config)) {
            $this->path = $config['path'];
            $this->generate = $config['generate'];

            return;
        }

        $this->path = $config;
        $this->generate = !! $config;
    }

    /**
     * Get the path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get generate.
     *
     * @return bool
     */
    public function generate() : bool
    {
        return $this->generate;
    }
}
