<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class ProviderMakeCommand extends GeneratorCommand
{
    use ModuleCommands;

    /** @var string */
    protected $argumentName = 'name';

    /** @var string */
    protected $signature = 'module:make-provider
                            {name : The service provider name}
                            {module? : The name of the module to create a new service provider for}
                            {--m|master : Indicates the master service provider}';

    /** @var string */
    protected $description = 'Create a new service provider class for the specified module.';

    protected function getDefaultNamespace(): string
    {
        /** @var \Rawilk\LaravelModules\Contracts\Repository $module */
        $module = $this->laravel['modules'];

        return $module->config('paths.generator.provider.namespace') ?: $module->config('paths.generator.provider.path', 'Providers');
    }

    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $generatorPath = GenerateConfigReader::read('provider');

        return $path . $generatorPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    protected function getTemplateContents(): string
    {
        /** @var \Rawilk\LaravelModules\Module $module */
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'NAMESPACE'        => $this->getClassNamespace($module),
            'CLASS'            => $this->getClass(),
            'LOWER_NAME'       => $module->getLowerName(),
            'MODULE'           => $this->getModuleName(),
            'NAME'             => $this->getFileName(),
            'STUDLY_NAME'      => $module->getStudlyName(),
            'MODULE_NAMESPACE' => $this->laravel['modules']->config('namespace'),
            'PATH_VIEWS'       => GenerateConfigReader::read('views')->getPath(),
            'PATH_LANG'        => GenerateConfigReader::read('lang')->getPath(),
            'PATH_CONFIG'      => GenerateConfigReader::read('config')->getPath(),
            'MIGRATIONS_PATH'  => GenerateConfigReader::read('migration')->getPath(),
            'FACTORIES_PATH'   => GenerateConfigReader::read('factory')->getPath(),
        ]))->render();
    }

    private function getStubName(): string
    {
        if ($this->option('master')) {
            return '/scaffold/provider.stub';
        }

        return '/provider.stub';
    }
}
