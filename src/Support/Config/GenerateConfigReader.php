<?php

namespace Rawilk\LaravelModules\Support\Config;

class GenerateConfigReader
{
	/**
	 * Read the given path.
	 *
	 * @param string $value
	 * @return \Rawilk\LaravelModules\Support\Config\GeneratorPath
	 */
	public static function read(string $value) : GeneratorPath
	{
		return new GeneratorPath(config("modules.paths.generator.{$value}"));
	}
}
