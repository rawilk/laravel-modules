<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class CommandMakeCommand extends GeneratorCommand
{
	use ModuleCommands;

	/**
	 * The name of argument name.
	 *
	 * @var string
	 */
	protected $argumentName = 'name';

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'module:make-command
							{name : The name of the command}
							{module? : The name of the module to make the command for}
							{--signature= : The name of the terminal command}
							{--argument=* : Any arguments the command should accept}
							{--option=* : Any options the command should accept}
							{--description= : The console command description}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate a new Artisan command for the specified module';

	/**
	 * Get default namespace.
	 *
	 * @return string
	 */
	public function getDefaultNamespace() : string
	{
		return $this->laravel['modules']->config('paths.generator.command.path', 'Console');
	}

	/**
	 * Get the template contents.
	 *
	 * @return string
	 */
	protected function getTemplateContents()
	{
		$module = $this->laravel['modules']->findOrFail($this->getModuleName());

		return (new Stub('/command.stub', [
			'SIGNATURE'    => $this->getSignature(),
			'NAMESPACE'    => $this->getClassNamespace($module),
			'CLASS'        => $this->getClass(),
			'DESCRIPTION'  => $this->getCommandDescription(),
		]))->render();
	}

	/**
	 * Get the name of the command being generated.
	 *
	 * @return string
	 */
	private function getSignature()
	{
		$signature = $this->option('signature') ?: 'command:name';

		foreach ($this->option('argument') as $argument) {
			$signature .= "\n\t\t\t\t\t\t\t {{$argument}}";
		}

		foreach ($this->option('option') as $option) {
			$signature .= "\n\t\t\t\t\t\t\t {--" . str_replace(['-', '--'], '', $option) . "}";
		}

		return $signature;
	}

	/**
	 * Get the description of the command.
	 *
	 * @return string
	 */
	protected function getCommandDescription()
	{
		return $this->option('description') ?: 'Command description';
	}

	/**
	 * Get the command's destination file path.
	 *
	 * @return string
	 */
	protected function getDestinationFilePath()
	{
		$path = $this->laravel['modules']->getModulePath($this->getModuleName());

		$commandPath = GenerateConfigReader::read('command');

		return $path . $commandPath->getPath() . '/' . $this->getFileName() . '.php';
	}
}
