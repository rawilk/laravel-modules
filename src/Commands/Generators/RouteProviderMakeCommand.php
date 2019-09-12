<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class RouteProviderMakeCommand extends GeneratorCommand
{
    use ModuleCommands;

    /** @var string */
    protected $argumentName = 'module';

    /** @var string */
    protected $signature = 'module:route-provider
                            {module? : The name of the module to create the provider for}
                            {--force : Force the operation to run when the file already exists}';

    /** @var string */
    protected $description = 'Create a new route provider for the specified module.';

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

    protected function getFileName(): string
    {
        return 'RouteServiceProvider';
    }

    protected function getTemplateContents(): string
    {
        /** @var \Rawilk\LaravelModules\Module $module */
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/route-provider.stub', [
            'NAMESPACE'        => $this->getClassNamespace($module),
            'CLASS'            => $this->getFileName(),
            'MODULE_NAMESPACE' => $this->laravel['modules']->config('namespace'),
            'MODULE'           => $this->getModuleName(),
            'WEB_ROUTES_PATH'  => $this->getWebRoutesPath(),
            'LOWER_NAME'       => $module->getLowerName(),
        ]))->render();
    }

    private function getWebRoutesPath(): string
    {
        return '/' . $this->laravel['modules']->config('stubs.files.routes/web', 'routes/web.php');
    }
}
