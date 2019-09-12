<?php

namespace Rawilk\LaravelModules\Generators;

use Illuminate\Filesystem\Filesystem;
use Rawilk\LaravelModules\Exceptions\FileAlreadyExists;

class FileGenerator extends Generator
{
    /** @var string */
    protected $contents;

    /** @var \Illuminate\Filesystem\Filesystem */
    protected $filesystem;

    /** @var bool */
    private $overwriteFile;

    /** @var string */
    protected $path;

    /**
     * @param string $path
     * @param string $contents
     * @param null|\Illuminate\Filesystem\Filesystem $filesystem
     */
    public function __construct(string $path, string $contents, ?Filesystem $filesystem = null)
    {
        $this->path = $path;
        $this->contents = $contents;
        $this->filesystem = $filesystem ?: new Filesystem;
    }

    public function generate()
    {
        $path = $this->getPath();

        if (! $this->filesystem->exists($path)) {
            return $this->filesystem->put($path, $this->getContents());
        }

        if ($this->overwriteFile) {
            return $this->filesystem->put($path, $this->getContents());
        }

        throw new FileAlreadyExists('File already exists!');
    }

    public function getContents(): string
    {
        return $this->contents;
    }

    public function getFilesystem(): Filesystem
    {
        return $this->filesystem;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setContents(string $contents): self
    {
        $this->contents = $contents;

        return $this;
    }

    public function setFilesystem(Filesystem $filesystem): self
    {
        $this->filesystem = $filesystem;

        return $this;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function withFileOverwrite(bool $overwrite = true): self
    {
        $this->overwriteFile = $overwrite;

        return $this;
    }
}
