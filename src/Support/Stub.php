<?php

namespace Rawilk\LaravelModules\Support;

class Stub
{
	/**
	 * The stub path.
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * The base path of the stub file.
	 *
	 * @var null|string
	 */
	protected static $basePath = null;

	/**
	 * The replacements to make.
	 *
	 * @var array
	 */
	protected $replaces = [];

	/**
	 * Create a new class instance.
	 *
	 * @param string $path
	 * @param array $replaces
	 */
	public function __construct($path, array $replaces = [])
	{
	    $this->path = $path;
	    $this->replaces = $replaces;
	}

	/**
	 * Create a new instance.
	 *
	 * @param string $path
	 * @param array $replaces
	 * @return static
	 */
	public static function create($path, array $replaces = [])
	{
		return new static($path, $replaces);
	}

	/**
	 * Set the stub path.
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
	 * Get the stub path.
	 *
	 * @return string
	 */
	public function getPath()
	{
		$path = static::getBasePath() . $this->path;

		return file_exists($path) ? $path : __DIR__ . '/../Commands/stubs' . $this->path;
	}

	/**
	 * Set the base stub path.
	 *
	 * @param string $path
	 */
	public static function setBasePath($path)
	{
		static::$basePath = $path;
	}

	/**
	 * Get the base stub path.
	 *
	 * @return null|string
	 */
	public static function getBasePath()
	{
		return static::$basePath;
	}

	/**
	 * Return a replaced version of the stub.
	 *
	 * @return string
	 */
	public function getContents()
	{
		$contents = file_get_contents($this->getPath());

		foreach ($this->replaces as $search => $replace) {
			$contents = str_replace('$' . strtoupper($search) . '$', $replace, $contents);
		}

		return $contents;
	}

	/**
	 * Alias to getContents()
	 *
	 * @return string
	 */
	public function render()
	{
		return $this->getContents();
	}

	/**
	 * Save stub to the given path.
	 *
	 * @param string $path
	 * @param string $filename
	 * @return bool|int
	 */
	public function saveTo($path, $filename)
	{
		return file_put_contents($path . '/' . $filename, $this->getContents());
	}

	/**
	 * Set the replacements.
	 *
	 * @param array $replaces
	 * @return $this
	 */
	public function replace(array $replaces = [])
	{
		$this->replaces = $replaces;

		return $this;
	}

	/**
	 * Get the replacements.
	 *
	 * @return array
	 */
	public function getReplaces()
	{
		return $this->replaces;
	}

	/**
	 * Handle magic method __toString.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->render();
	}
}
