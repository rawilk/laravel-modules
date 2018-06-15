<?php

namespace Rawilk\LaravelModules\Generators;

use Illuminate\Filesystem\Filesystem;
use Rawilk\LaravelModules\Exceptions\FileAlreadyExistsException;

class FileGenerator extends Generator
{
	/**
	 * The path of the file.
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * The contents of the file.
	 *
	 * @var string
	 */
	protected $contents;

	/**
	 * The laravel filesystem.
	 *
	 * @var \Illuminate\Filesystem\Filesystem|null
	 */
	protected $filesystem;

	/**
	 * Create a new class instance.
	 *
	 * @param string $path
	 * @param string $contents
	 * @param \Illuminate\Filesystem\Filesystem|null $filesystem
	 */
	public function __construct($path, $contents, $filesystem = null)
	{
		$this->path = $path;
		$this->contents = $contents;
		$this->filesystem = $filesystem ?: new Filesystem();
	}

	/**
	 * Get contents.
	 *
	 * @return string
	 */
	public function getContents()
	{
		return $this->contents;
	}

	/**
	 * Set contents.
	 *
	 * @param string $contents
	 * @return $this
	 */
	public function setContents($contents)
	{
		$this->contents = $contents;

		return $this;
	}

	/**
	 * Get the filesystem instance.
	 *
	 * @return \Illuminate\Filesystem\Filesystem|null
	 */
	public function getFilesystem()
	{
		return $this->filesystem;
	}

	/**
	 * Set the filesystem instance.
	 *
	 * @param \Illuminate\Filesystem\Filesystem $filesystem
	 * @return $this
	 */
	public function setFilesystem(Filesystem $filesystem)
	{
		$this->filesystem = $filesystem;

		return $this;
	}

	/**
	 * Get the path of the file.
	 *
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * Set the path of the file.
	 *
	 * @param string $path
	 * @return $this
	 */
	public function setPath($path)
	{
		$this->path = $path;

		return $this;
	}

	/**
	 * Generate the file.
	 *
	 * @throws \Rawilk\LaravelModules\Exceptions\FileAlreadyExistsException
	 */
	public function generate()
	{
		if (! $this->filesystem->exists($path = $this->getPath())) {
			return $this->filesystem->put($path, $this->getContents());
		}

		throw new FileAlreadyExistsException('File already exists!');
	}
}
