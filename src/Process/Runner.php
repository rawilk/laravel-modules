<?php

namespace Rawilk\LaravelModules\Process;

use Rawilk\LaravelModules\Contracts\RunableInterface;
use Rawilk\LaravelModules\Repository;

class Runner implements RunableInterface
{
	/**
	 * The module instance.
	 *
	 * @var \Rawilk\LaravelModules\Repository
	 */
	protected $module;

	/**
	 * The constructor.
	 *
	 * @param \Rawilk\LaravelModules\Repository $module
	 */
	public function __construct(Repository $module)
	{
		$this->module = $module;
	}

	/**
	 * Run the given command.
	 *
	 * @param string $command
	 */
	public function run($command)
	{
		passthru($command);
	}
}
