<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Illuminate\Support\Str;
use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class ControllerMakeCommand extends GeneratorCommand
{
    use ModuleCommands;

    /** @var string */
    protected $argumentName = 'controller';

    /** @var string */
    protected $signature = 'module:make-controller
                            {controller : The name of the controller class}
                            {module? : The name of the module to create the controller for}
                            {--p|plain : Generate a plain controller}
                            {--base_class= : Override the default base controller class (from config)}';

    /** @var string */
    protected $description = 'Generate a new controller for the specified module.';

    protected function getDefaultNamespace(): string
    {
        /** @var \Rawilk\LaravelModules\Contracts\Repository $module */
        $module = $this->laravel['modules'];

        return $module->config('paths.generator.controller.namespace') ?: $module->config('paths.generator.controller.path', 'Http/Controllers');
    }

    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $controllerPath = GenerateConfigReader::read('controller');

        return $path . $controllerPath->getPath() . '/' . $this->getControllerName() . '.php';
    }

    protected function getTemplateContents(): string
    {
        /** @var \Rawilk\LaravelModules\Module $module */
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'MODULENAME'       => $module->getStudlyName(),
            'CONTROLLERNAME'   => $this->getControllerName(),
            'NAMESPACE'        => $module->getStudlyName(),
            'CLASS_NAMESPACE'  => $this->getClassNamespace($module),
            'CLASS'            => $this->getControllerNameWithoutNamespace(),
            'LOWER_NAME'       => $module->getLowerName(),
            'MODULE'           => $this->getModuleName(),
            'NAME'             => $this->getModuleName(),
            'STUDLY_NAME'      => $module->getStudlyName(),
            'MODULE_NAMESPACE' => $this->laravel['modules']->config('namespace'),
            'BASE_CLASS'       => $this->getBaseClass('controller'),
            'BASE_CLASS_SHORT' => $this->getBaseClass('controller', true),
        ]))->render();
    }

    private function getControllerName(): string
    {
        $controller = Str::studly($this->argument('controller'));

        if (! Str::contains(strtolower($controller), 'controller')) {
            $controller .= 'Controller';
        }

        return $controller;
    }

    private function getControllerNameWithoutNamespace(): string
    {
        return class_basename($this->getControllerName());
    }

    private function getStubName(): string
    {
        if ($this->option('plain') === true) {
            return '/controller-plain.stub';
        }

        return '/controller.stub';
    }
}
