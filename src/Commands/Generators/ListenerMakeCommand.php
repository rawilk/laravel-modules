<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Rawilk\LaravelModules\Module;
use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class ListenerMakeCommand extends GeneratorCommand
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
	protected $signature = 'module:make-listener
							{name : The name of the listener}
							{module? : The name of the module to make listener for}
							{--e|event : The event class being listened for}
							{--queued : Indicates the event listener should be queued}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new event listener class for the specified module';

	/**
	 * Get the template contents.
	 *
	 * @return string
	 */
	protected function getTemplateContents()
	{
		$module = $this->laravel['modules']->findOrFail($this->getModuleName());

		return (new Stub($this->getStubName(), [
			'NAMESPACE'      => $this->getNamespace($module),
			'EVENTNAME'      => $this->getEventName($module),
			'SHORTEVENTNAME' => $this->option('event'),
			'CLASS'          => $this->getClass(),
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

		$listenerPath = GenerateConfigReader::read('listener');

		return $path . $listenerPath->getPath() . '/' . $this->getFileName() . '.php';
	}

	/**
	 * Get the event name.
	 *
	 * @param \Rawilk\LaravelModules\Module $module
	 * @return string
	 */
	private function getEventName(Module $module)
	{
		$eventPath = GenerateConfigReader::read('event');

		return $this->getClassNamespace($module) . '\\' . $eventPath->getPath() . '\\' . $this->option('event');
	}

	/**
	 * Get the listener namespace.
	 *
	 * @param \Rawilk\LaravelModules\Module $module
	 * @return string
	 */
	private function getNamespace(Module $module)
	{
		$listenerPath = GenerateConfigReader::read('listener');

		$namespace = str_replace('/', '\\', $listenerPath->getPath());

		return $this->getClassNamespace($module) . '\\' . $namespace;
	}

	/**
	 * Get the name of the stub file.
	 *
	 * @return string
	 */
	private function getStubName() : string
	{
		if ($this->option('queued')) {
			if ($this->option('event')) {
				return '/listener-queued.stub';
			}

			return '/listener-queued-duck.stub';
		}

		if ($this->option('event')) {
			return '/listener.stub';
		}

		return '/listener-duck.stub';
	}
}
