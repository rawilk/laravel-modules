<?php

namespace Rawilk\LaravelModules\Publishing;

use Rawilk\LaravelModules\Migrations\Migrator;

class MigrationPublisher extends AssetPublisher
{
	/**
	 * The migrator instance.
	 *
	 * @var \Rawilk\LaravelModules\Migrations\Migrator
	 */
	private $migrator;

	/**
	 * Create a new class instance.
	 *
	 * @param \Rawilk\LaravelModules\Migrations\Migrator
	 */
	public function __construct(Migrator $migrator)
	{
	    $this->migrator = $migrator;

	    parent::__construct($migrator->getModule());
	}

	/**
	 * Get the destination path.
	 *
	 * @return string
	 */
	public function getDestinationPath()
	{
		return $this->repository->config('paths.migration');
	}

	/**
	 * Get the source path.
	 *
	 * @return string
	 */
	public function getSourcePath()
	{
		return $this->migrator->getPath();
	}
}
