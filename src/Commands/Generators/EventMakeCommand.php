<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class EventMakeCommand extends GeneratorCommand
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
	protected $signature = 'module:make-event
							{name : The name of the event}
							{module? : The name of the module to create the event for}
							{--p|plain : Create a plain event}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new event class for the specified module';

	/**
	 * Get the template contents.
	 *
	 * @return string
	 */
	public function getTemplateContents()
	{
		$module = $this->laravel['modules']->findOrFail($this->getModuleName());

		return (new Stub($this->getStubPath(), [
			'NAMESPACE' => $this->getClassNamespace($module),
			'CLASS'     => $this->getClass(),
		]))->render();
	}

	/**
	 * Get the path of the stub file to use.
	 *
	 * @return string
	 */
	protected function getStubPath()
	{
		return $this->option('plain') ? '/event-plain.stub' : '/event.stub';
	}

	/**
	 * Get the destination file path.
	 *
	 * @return string
	 */
	public function getDestinationFilePath()
	{
		$path = $this->laravel['modules']->getModulePath($this->getModuleName());

		$eventPath = GenerateConfigReader::read('event');

		return $path . $eventPath->getPath() . '/' . $this->getFileName() . '.php';
	}

	/**
	 * Get default namespace.
	 *
	 * @return string
	 */
	public function getDefaultNamespace() : string
	{
		return $this->laravel['modules']->config('paths.generator.event.path', 'Events');
	}
}
