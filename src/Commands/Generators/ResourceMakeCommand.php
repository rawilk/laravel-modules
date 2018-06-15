<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class ResourceMakeCommand extends GeneratorCommand
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
	protected $signature = 'module:make-resource
							{name : The name of the resource class}
							{module? : The name of the module to create the resource for}
							{--c|collection : Create a resource collection class}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new resource class for the specified module';

	/**
	 * Get the template contents.
	 *
	 * @return string
	 */
	protected function getTemplateContents()
	{
		$module = $this->laravel['modules']->findOrFail($this->getModuleName());

		return (new Stub($this->getStubName(), [
			'NAMESPACE' => $this->getClassNamespace($module),
			'CLASS'     => $this->getClass(),
		]))->render();
	}

	/**
	 * Get the destination file path.
	 *
	 * @return string
	 */
	protected function getDestinationFilePath()
	{
		$path = $this->laravel['modules']->getModulePath($this->getModuleName());

		$resourcePath = GenerateConfigReader::read('resource');

		return $path . $resourcePath->getPath() . '/' . $this->getFileName() . '.php';
	}

	/**
	 * Get default namespace.
	 *
	 * @return string
	 */
	public function getDefaultNamespace() : string
	{
		return $this->laravel['modules']->config('paths.generator.resource.path', 'Transformers');
	}

	/**
	 * Determine if the command is generating a resource collection.
	 *
	 * @return bool
	 */
	private function collection() : bool
	{
		return $this->option('collection') || ends_with($this->argument($this->argumentName), 'Collection');
	}

	/**
	 * Get the stub file name.
	 *
	 * @return string
	 */
	private function getStubName() : string
	{
		if ($this->collection()) {
			return '/resource-collection.stub';
		}

		return '/resource.stub';
	}
}
