<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Illuminate\Support\Str;
use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\CanClearModulesCache;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class SeedMakeCommand extends GeneratorCommand
{
    use ModuleCommands, CanClearModulesCache;

    /** @var string */
    protected $argumentName = 'name';

    /** @var string */
    protected $signature = 'module:make-seed
                            {name : The name of the seeder class}
                            {module? : The name of the module to create the seeder for}
                            {--m|master : Indicates the seeder will be the master database seeder}';

    /** @var string */
    protected $description = 'Generate a new seeder for the specified module.';

    protected function getDefaultNamespace(): string
    {
        /** @var \Rawilk\LaravelModules\Contracts\Repository $module */
        $module = $this->laravel['modules'];

        return $module->config('paths.generator.seeder.namespace') ?: $module->config('paths.generator.seeder.path', 'database/seeds');
    }

    protected function getDestinationFilePath(): string
    {
        $this->clearCache();

        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $seederPath = GenerateConfigReader::read('seeder');

        return $path . $seederPath->getPath() . '/' . $this->getSeederName() . '.php';
    }

    protected function getTemplateContents(): string
    {
        /** @var \Rawilk\LaravelModules\Module $module */
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/seeder.stub', [
            'NAME'      => $this->getSeederName(),
            'MODULE'    => $this->getModuleName(),
            'NAMESPACE' => $this->getClassNamespace($module),
        ]))->render();
    }

    private function getSeederName(): string
    {
        $end = $this->option('master') ? 'DatabaseSeeder' : 'TableSeeder';

        return Str::studly($this->argument('name')) . $end;
    }
}
