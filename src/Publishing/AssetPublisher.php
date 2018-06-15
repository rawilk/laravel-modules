<?php

namespace Rawilk\LaravelModules\Publishing;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;

class AssetPublisher extends Publisher
{
	/**
	 * Determine if the result message will shown in the console.
	 *
	 * @var bool
	 */
	protected $showMessage = false;

	/**
	 * Get the destination path.
	 *
	 * @return string
	 */
	public function getDestinationPath()
	{
		return $this->repository->assetPath($this->module->getLowerName());
	}

	/**
	 * Get the source path.
	 *
	 * @return string
	 */
	public function getSourcePath()
	{
		return $this->getModule()->getExtraPath(
			GenerateConfigReader::read('assets')->getPath()
		);
	}
}
