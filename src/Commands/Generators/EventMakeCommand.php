<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class EventMakeCommand extends GeneratorCommand
{
    use ModuleCommands;

    /** @var string */
    protected $argumentName = 'name';

    /** @var string */
    protected $signature = 'module:make-event
                            {name : The name of the event}
                            {module? : The name of the module to create the event for}
                            {--p|plain : Create a plain event}';

    /** @var string */
    protected $description = 'Create a new event class for the specified module.';

    protected function getDefaultNamespace(): string
    {
        /** @var \Rawilk\LaravelModules\Contracts\Repository $module */
        $module = $this->laravel['modules'];

        return $module->config('paths.generator.event.namespace') ?: $module->config('paths.generator.event.path', 'Events');
    }

    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $eventPath = GenerateConfigReader::read('event');

        return $path . $eventPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    protected function getTemplateContents(): string
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubPath(), [
            'NAMESPACE' => $this->getClassNamespace($module),
            'CLASS'     => $this->getClass(),
        ]))->render();
    }

    private function getStubPath(): string
    {
        return $this->option('plain') === true
            ? '/event-plain.stub'
            : '/event.stub';
    }
}
