<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Illuminate\Support\Str;
use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;
use Illuminate\Database\Eloquent\Model;

class RepositoryMakeCommand extends GeneratorCommand
{
    use ModuleCommands;

    /** @var string */
    protected $argumentName = 'repository';

    /** @var string */
    protected $signature = 'module:make-repository
                            {repository : The name of the repository}
                            {module? : The name of the module to create the repository for}
                            {--base_class= : Override the default base repository class (from config)}
                            {--model= : The model the repository is for}';

    /** @var string */
    protected $description = 'Generate a new repository for the specified module.';

    protected function getDefaultNamespace(): string
    {
        /** @var \Rawilk\LaravelModules\Contracts\Repository $module */
        $module = $this->laravel['modules'];

        return $module->config('paths.generator.repository.namespace') ?: $module->config('paths.generator.repository.path', 'Repositories');
    }

    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $repositoryPath = GenerateConfigReader::read('repository');

        return $path . $repositoryPath->getPath() . '/' . $this->getRepositoryName() . '.php';
    }

    protected function getTemplateContents(): string
    {
        /** @var \Rawilk\LaravelModules\Module $module */
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/repository.stub', [
            'NAMESPACE'        => $this->getClassNamespace($module),
            'CLASS'            => class_basename($this->getRepositoryName()),
            'BASE_CLASS'       => $this->getBaseClass('repository'),
            'BASE_CLASS_SHORT' => $this->getBaseClass('repository', true),
            'MODEL'            => $this->getModel(),
            'MODEL_NAMESPACE'  => $this->getModel(false),
        ]))->render();
    }

    private function getModel(bool $returnBasename = true): string
    {
        $model = Str::studly($this->option('model'));

        if (! $model) {
            $model = Model::class;
        }

        $model = str_replace('/', '\\', $model);

        return $returnBasename
            ? class_basename($model) . '::class'
            : $model;
    }

    private function getRepositoryName(): string
    {
        $repository = Str::studly($this->argument('repository'));

        if (! Str::contains(strtolower($repository), 'repository')) {
            $repository .= 'Repository';
        }

        return $repository;
    }
}
