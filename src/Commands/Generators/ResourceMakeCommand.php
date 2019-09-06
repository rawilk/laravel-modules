<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Illuminate\Support\Str;
use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class ResourceMakeCommand extends GeneratorCommand
{
    use ModuleCommands;

    /** @var string */
    protected $argumentName = 'name';

    /** @var string */
    protected $signature = 'module:make-resource
                            {name : The name of the resource class}
                            {module? : The name of the module to create the resource for}
                            {--c|collection : Indicates a resource collection should be created}';

    /** @var string */
    protected $description = 'Create a new resource class for the specified module.';

    protected function getDefaultNamespace(): string
    {
        /** @var \Rawilk\LaravelModules\Contracts\Repository $module */
        $module = $this->laravel['modules'];

        return $module->config('paths.generator.resource.namespace') ?: $module->config('paths.generator.resource.path', 'Transformers');
    }

    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $resourcePath = GenerateConfigReader::read('resource');

        return $path . $resourcePath->getPath() . '/' . $this->getFileName() . '.php';
    }

    protected function getTemplateContents(): string
    {
        /** @var \Rawilk\LaravelModules\Module $module */
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'NAMESPACE' => $this->getClassNamespace($module),
            'CLASS'     => $this->getClass(),
        ]))->render();
    }

    private function collection(): bool
    {
        return $this->option('collection')
            || Str::endsWith($this->argument('name'), 'Collection');
    }

    private function getStubName(): string
    {
        if ($this->collection()) {
            return '/resource-collection.stub';
        }

        return '/resource.stub';
    }
}
