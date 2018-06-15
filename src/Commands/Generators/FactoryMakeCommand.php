<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class FactoryMakeCommand extends GeneratorCommand
{
	use ModuleCommands;

	/**
	 * The name of 'name' argument.
	 *
	 * @var string
	 */
	protected $argumentName = 'name';

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'module:make-factory
						    {name : The name of the factory}
						    {module? : The name of the module to create the factory for}
						    {--model= : The name of the model the factory is for}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new model factory for the specified module.';

	/**
	 * Get the template contents.
	 *
	 * @return string
	 */
	protected function getTemplateContents()
	{
		return (new Stub('/factory.stub', [
			'MODEL' => $this->getModelName()
		]))->render();
	}

	/**
	 * Get the name of the factory model.
	 *
	 * @return string
	 */
	private function getModelName()
	{
		$model = $this->option('model') ?: 'Model::class';

		if (! str_contains(strtolower($model), '::class')) {
			$model .= '::class';
		}

		return $model;
	}

	/**
	 * Get the destination file path.
	 *
	 * @return string
	 */
	protected function getDestinationFilePath()
	{
		$path = $this->laravel['modules']->getModulePath($this->getModuleName());

		$factoryPath = GenerateConfigReader::read('factory');

		return $path . $factoryPath->getPath() . '/' . $this->getFileName() . '.php';
	}
}
