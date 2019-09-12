<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Illuminate\Support\Str;
use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class FactoryMakeCommand extends GeneratorCommand
{
    use ModuleCommands;

    /** @var string */
    protected $argumentName = 'name';

    /** @var string */
    protected $signature = 'module:make-factory
                            {name : The name of the factory}
                            {module? : The name of the module to create the factory for}
                            {--model= : The name of the model the factory is for}';

    /** @var string */
    protected $description = 'Create a new model factory for the specified module.';

    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $factoryPath = GenerateConfigReader::read('factory');

        return $path . $factoryPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    protected function getTemplateContents(): string
    {
        return (new Stub('/factory.stub', [
            'MODEL' => $this->getModelName()
        ]))->render();
    }

    private function getModelName(): string
    {
        $model = $this->option('model') ?: 'Model::class';

        if (! Str::contains(strtolower($model), '::class')) {
            $model .= '::class';
        }

        return $model;
    }
}
