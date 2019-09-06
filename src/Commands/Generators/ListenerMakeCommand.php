<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Rawilk\LaravelModules\Module;
use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class ListenerMakeCommand extends GeneratorCommand
{
    use ModuleCommands;

    /** @var string */
    protected $argumentName = 'name';

    /** @var string */
    protected $signature = 'module:make-listener
                            {name : The name of the listener}
                            {module? : The name of the module to create a new listener for}
                            {--e|event= : The event class being listened for}
                            {--queued : Indicates the event listener should be queued}';

    /** @var string */
    protected $description = 'Create a new event listener class for the specified module.';

    protected function getDefaultNamespace(): string
    {
        /** @var \Rawilk\LaravelModules\Contracts\Repository $module */
        $module = $this->laravel['modules'];

        return $module->config('paths.generator.listener.namespace') ?: $module->config('paths.generator.listener.path', 'Listeners');
    }

    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $listenerPath = GenerateConfigReader::read('listener');

        return $path . $listenerPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    protected function getTemplateContents(): string
    {
        /** @var \Rawilk\LaravelModules\Module $module */
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'NAMESPACE'      => $this->getClassNamespace($module),
            'EVENTNAME'      => $this->getEventName($module),
            'SHORTEVENTNAME' => $this->option('event'),
            'CLASS'          => $this->getClass(),
        ]))->render();
    }

    private function getEventName(Module $module): string
    {
        $eventPath = GenerateConfigReader::read('event');

        return $this->getClassNamespace($module) . '\\' . $eventPath->getPath() . '\\' . $this->option('event');
    }

    private function getStubName(): string
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
