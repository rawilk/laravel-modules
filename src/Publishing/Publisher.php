<?php

namespace Rawilk\LaravelModules\Publishing;

use Illuminate\Console\Command;
use Rawilk\LaravelModules\Contracts\PublisherInterface;
use Rawilk\LaravelModules\Module;
use Rawilk\LaravelModules\Repository;

abstract class Publisher implements PublisherInterface
{
    /**
     * The name of module to use.
     *
     * @var string
     */
    protected $module;

    /**
     * The modules repository instance.
     *
     * @var \Rawilk\LaravelModules\Repository
     */
    protected $repository;

    /**
     * The laravel console instance.
     *
     * @var \Illuminate\Console\Command
     */
    protected $console;

    /**
     * The success message will to display in the console.
     *
     * @var string
     */
    protected $success;

    /**
     * The error message to display in the console.
     *
     * @var string
     */
    protected $error = '';

    /**
     * Determine if the result message will shown in the console.
     *
     * @var bool
     */
    protected $showMessage = true;

    /**
     * @param \Rawilk\LaravelModules\Module $module
     */
    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    /**
     * Show the result message.
     *
     * @return self
     */
    public function showMessage()
    {
        $this->showMessage = true;

        return $this;
    }

    /**
     * Hide the result message.
     *
     * @return self
     */
    public function hideMessage()
    {
        $this->showMessage = false;

        return $this;
    }

    /**
     * Get the module instance.
     *
     * @return \Rawilk\LaravelModules\Module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set the modules repository instance.
     *
     * @param \Rawilk\LaravelModules\Repository $repository
     * @return $this
     */
    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * Get the module repository instance.
     *
     * @return \Rawilk\LaravelModules\Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Set the console instance.
     *
     * @param \Illuminate\Console\Command $console
     * @return $this
     */
    public function setConsole(Command $console)
    {
        $this->console = $console;

        return $this;
    }

    /**
     * Get the console instance.
     *
     * @return \Illuminate\Console\Command
     */
    public function getConsole()
    {
        return $this->console;
    }

    /**
     * Get the laravel filesystem instance.
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function getFilesystem()
    {
        return $this->repository->getFiles();
    }

    /**
     * Get the destination path.
     *
     * @return string
     */
    abstract public function getDestinationPath();

    /**
     * Get the source path.
     *
     * @return string
     */
    abstract public function getSourcePath();

    /**
     * Publish something.
     *
     * @throws \RuntimeException
     */
    public function publish()
    {
        if (! $this->console instanceof Command) {
            $message = "The 'console' property must instance of \\Illuminate\\Console\\Command.";

            throw new \RuntimeException($message);
        }

        if (! $this->getFilesystem()->isDirectory($sourcePath = $this->getSourcePath())) {
            return;
        }

        if (! $this->getFilesystem()->isDirectory($destinationPath = $this->getDestinationPath())) {
            $this->getFilesystem()->makeDirectory($destinationPath, 0775, true);
        }

        if ($this->getFilesystem()->copyDirectory($sourcePath, $destinationPath)) {
            if ($this->showMessage) {
                $this->console->line("<info>Published</info>: {$this->module->getStudlyName()}");
            }
        } else {
            $this->console->error($this->error);
        }
    }
}
