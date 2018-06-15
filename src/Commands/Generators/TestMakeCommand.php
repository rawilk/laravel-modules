<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class TestMakeCommand extends GeneratorCommand
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
	protected $signature = 'module:make-test
							{name : The name of the test class}
							{module? : The name of the module to create the test for}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new test class for the specified module';

	/**
	 * Get the template contents.
	 *
	 * @return string
	 */
	protected function getTemplateContents()
	{
		$module = $this->laravel['modules']->findOrFail($this->getModuleName());

		return (new Stub('/unit-test.stub', [
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

		$testPath = GenerateConfigReader::read('test');

		return $path . $testPath->getPath() . '/' . $this->getFileName() . '.php';
	}

	/**
	 * Get default namespace.
	 *
	 * @return string
	 */
	public function getDefaultNamespace() : string
	{
		return $this->laravel['modules']->config('paths.generator.test.path', 'Tests');
	}
}
