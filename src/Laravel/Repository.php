<?php

namespace Rawilk\LaravelModules\Laravel;

use Rawilk\LaravelModules\Repository as BaseRepository;

class Repository extends BaseRepository
{
	/**
	 * Create a new Module instance
	 *
	 * @param array $args
	 * @return \Rawilk\LaravelModules\Module
	 */
	protected function createModule(...$args)
	{
		return new Module(...$args);
	}
}
