<?php

namespace Rawilk\LaravelModules\Publishing;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Rawilk\LaravelModules\Contracts\Publisher as PublisherContract;
use Rawilk\LaravelModules\Contracts\Repository;
use Rawilk\LaravelModules\Module;
use RuntimeException;

abstract class Publisher implements PublisherContract
{
    /** @var \Illuminate\Console\Command */
    protected $console;

    /** @var string */
    protected $error = '';

    /** @var string */
    protected $module;

    /** @var \Rawilk\LaravelModules\Contracts\Repository */
    protected $repository;

    /** @var bool */
    protected $showMessage = true;

    /** @var string */
    protected $success;

    /**
     * @param \Rawilk\LaravelModules\Module $module
     */
    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    abstract public function getDestinationPath(): string;

    abstract public function getSourcePath(): string;

    public function getConsole(): Command
    {
        return $this->console;
    }

    public function getFilesystem(): Filesystem
    {
        return $this->repository->getFiles();
    }

    public function getModule(): Module
    {
        return $this->module;
    }

    public function getRepository(): Repository
    {
        return $this->repository;
    }

    public function hideMessage(): self
    {
        $this->showMessage = false;

        return $this;
    }

    public function publish(): void
    {
        if (! $this->console instanceof Command) {
            $message = "The 'console' property must be an instance of " . Command::class . '.';

            throw new RuntimeException($message);
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

    public function setConsole(Command $console): self
    {
        $this->console = $console;

        return $this;
    }

    public function setRepository(Repository $repository): self
    {
        $this->repository = $repository;

        return $this;
    }

    public function showMessage(): self
    {
        $this->showMessage = true;

        return $this;
    }
}
