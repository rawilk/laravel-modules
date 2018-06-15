<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class ControllerMakeCommand extends GeneratorCommand
{
	use ModuleCommands;

	/**
	 * The name of argument being used.
	 *
	 * @var string
	 */
	protected $argumentName = 'controller';

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'module:make-controller
							{controller : The name of the controller class}
							{module? : The name of the module to make the controller for}
							{--p|plain : Generate a plain controller}
							{--base_class= : Override the default base controller class}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate a new controller for the specified module.';

	/**
	 * Get controller name.
	 *
	 * @return string
	 */
	public function getDestinationFilePath()
	{
		$path = $this->laravel['modules']->getModulePath($this->getModuleName());

		$controllerPath = GenerateConfigReader::read('controller');

		return $path . $controllerPath->getPath() . '/' . $this->getControllerName() . '.php';
	}

	/**
	 * Get the template contents.
	 *
	 * @return string
	 */
	protected function getTemplateContents()
	{
		$module = $this->laravel['modules']->findOrFail($this->getModuleName());

		return (new Stub($this->getStubName(), [
			'MODULENAME'        => $module->getStudlyName(),
			'CONTROLLERNAME'    => $this->getControllerName(),
			'NAMESPACE'         => $module->getStudlyName(),
			'CLASS_NAMESPACE'   => $this->getClassNamespace($module),
			'CLASS'             => class_basename($this->getControllerName()),
			'LOWER_NAME'        => $module->getLowerName(),
			'MODULE'            => $this->getModuleName(),
			'NAME'              => $this->getModuleName(),
			'STUDLY_NAME'       => $module->getStudlyName(),
			'MODULE_NAMESPACE'  => $this->laravel['modules']->config('namespace'),
			'BASE_CLASS'        => $this->getBaseClass('controller'),
			'BASE_CLASS_SHORT'  => $this->getBaseClass('controller', true),
		]))->render();
	}

	/**
	 * Get the controller name.
	 *
	 * @return string
	 */
	protected function getControllerName()
	{
		$controller = studly_case($this->argument('controller'));

		if (str_contains(strtolower($controller), 'controller') === false) {
			$controller .= 'Controller';
		}

		return $controller;
	}

	/**
	 * Get default namespace.
	 *
	 * @return string
	 */
	public function getDefaultNamespace() : string
	{
		return $this->laravel['modules']->config('paths.generator.controller.path', 'Http/Controllers');
	}

	/**
	 * Get the stub file name based on the plain option
	 *
	 * @return string
	 */
	private function getStubName()
	{
		if ($this->option('plain')) {
			return '/controller-plain.stub';
		}

		return '/controller.stub';
	}
}
