<?php

namespace Rawilk\LaravelModules\Process;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Rawilk\LaravelModules\Contracts\Repository;
use Symfony\Component\Process\Process;

class Installer
{
    /** @var \Illuminate\Console\Command */
    protected $console;

    /** @var string */
    protected $name;

    /** @var string */
    protected $path;

    /** @var \Rawilk\LaravelModules\Contracts\Repository */
    protected $repository;

    /** @var int */
    protected $timeout = 3360;

    /** @var bool */
    private $tree;

    /** @var string|null */
    private $type;

    /** @var string */
    protected $version;

    /**
     * @param string $name
     * @param string|null $version
     * @param string|null $type
     * @param bool $tree
     */
    public function __construct(string $name, ?string $version = null, ?string $type = null, bool $tree = false)
    {
        $this->name = $name;
        $this->version = $version;
        $this->type = $type;
        $this->tree = $tree;
    }

    public function getBranch(): string
    {
        return $this->version ?? 'master';
    }

    public function getDestinationPath(): string
    {
        if ($this->path) {
            return $this->path;
        }

        return $this->repository->getModulePath($this->getModuleName());
    }

    public function getModuleName(): string
    {
        $parts = explode('/', $this->name);

        return Str::studly(end($parts));
    }

    public function getPackageName(): string
    {
        if ($this->version === null) {
            return "{$this->name}:dev-master";
        }


        return "{$this->name}:{$this->version}";
    }

    public function getProcess(): Process
    {
        if ($this->type) {
            if ($this->tree) {
                return $this->installViaSubtree();
            }

            return $this->installViaGit();
        }

        return $this->installViaComposer();
    }

    public function getRepoUrl(): ?string
    {
        switch ($this->type) {
            case 'github':
                return "git@github.com:{$this->name}.git";
            case 'github-https':
                return "https://github.com/{$this->name}.git";
            case 'gitlab':
                return "git@gitlab.com:{$this->name}.git";
            case 'bitbucket':
                return "git@bitbucket.org:{$this->name}.git";
            default:
                // Check of type 'scheme://host/path'
                if (filter_var($this->type, FILTER_VALIDATE_URL)) {
                    return $this->type;
                }

                // Check of type 'user@host'
                if (filter_var($this->type, FILTER_VALIDATE_EMAIL)) {
                    return "{$this->type}:{$this->name}.git";
                }

                return null;
        }
    }

    public function installViaComposer(): Process
    {
        return Process::fromShellCommandline(sprintf(
            'cd %s && composer require %s',
            base_path(),
            $this->getPackageName()
        ));
    }

    public function installViaGit(): Process
    {
        return Process::fromShellCommandline(sprintf(
            'cd %s && git clone %s %s && cd %s && git checkout %s',
            base_path(),
            $this->getRepoUrl(),
            $this->getDestinationPath(),
            $this->getDestinationPath(),
            $this->getBranch()
        ));
    }

    public function installViaSubtree(): Process
    {
        return Process::fromShellCommandline(sprintf(
            'cd %s && git remote add %s %s && git subtree add --prefix=%s --squash %s %s',
            base_path(),
            $this->getModuleName(),
            $this->getRepoUrl(),
            $this->getDestinationPath(),
            $this->getModuleName(),
            $this->getBranch()
        ));
    }

    public function run(): Process
    {
        $process = $this->getProcess();

        $process->setTimeout($this->timeout);

        if ($this->console instanceof Command) {
            $process->run(function ($type, $line) {
                $this->console->line($line);
            });
        }

        return $process;
    }

    public function setConsole(Command $console): self
    {
        $this->console = $console;

        return $this;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function setRepository(Repository $repository): self
    {
        $this->repository = $repository;

        return $this;
    }

    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;

        return $this;
    }
}
