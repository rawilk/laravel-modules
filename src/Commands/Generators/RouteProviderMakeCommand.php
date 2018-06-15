<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class RouteProviderMakeCommand extends GeneratorCommand
{
	use ModuleCommands;

	/**
	 * The name of 'name' argument.
	 *
	 * @var string
	 */
	protected $argumentName = 'module';

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'module:route-provider
							{module? : The name of the module to create the provider for}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new route service provider for the specified module';

	/**
	 * Get the template contents.
	 *
	 * @return string
	 */
	protected function getTemplateContents()
	{
		$module = $this->laravel['modules']->findOrFail($this->getModuleName());

		return (new Stub('/route-provider.stub', [
			'NAMESPACE'         => $this->getClassNamespace($module),
			'CLASS'             => $this->getFileName(),
			'MODULE_NAMESPACE'  => $this->laravel['modules']->config('namespace'),
			'MODULE'            => $this->getModuleName(),
			'ROUTES_PATH'       => $this->getRoutesPath(),
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

		$generatorPath = GenerateConfigReader::read('provider');

		return $path . $generatorPath->getPath() . '/' . $this->getFileName() . '.php';
	}

	/**
	 * Get the name of the file.
	 *
	 * @return string
	 */
	protected function getFileName() : string
	{
		return 'RouteServiceProvider';
	}

	/**
	 * Get default namespace.
	 *
	 * @return string
	 */
	public function getDefaultNamespace() : string
	{
		return $this->laravel['modules']->config('paths.generator.provider.path', 'Providers');
	}

	/**
	 * Get the path of the main route file.
	 *
	 * @return string
	 */
	private function getRoutesPath()
	{
		return '/' . $this->laravel['config']->get('stubs.files.routes', 'routes/web.php');
	}
}
