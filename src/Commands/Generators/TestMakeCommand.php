<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class TestMakeCommand extends GeneratorCommand
{
    use ModuleCommands;

    /** @var string */
    protected $argumentName = 'name';

    /** @var string */
    protected $signature = 'module:make-test
                            {name : The name of the test}
                            {module? : The name of the module to create the test for}
                            {--feature : Indicates the test is a feature test}';

    /** @var string */
    protected $description = 'Create a new test class for the specified module.';

    protected function getDefaultNamespace(): string
    {
        /** @var \Rawilk\LaravelModules\Contracts\Repository $module */
        $module = $this->laravel['modules'];

        if ($this->option('feature')) {
            return $module->config('paths.generator.test-feature.namespace') ?: $module->config('paths.generator.test-feature.path', 'tests/Feature');
        }

        return $module->config('paths.generator.test.namespace') ?: $module->config('paths.generator.test.path', 'tests/Unit');
    }

    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        if ($this->option('feature')) {
            $testPath = GenerateConfigReader::read('test-feature');
        } else {
            $testPath = GenerateConfigReader::read('test');
        }

        return $path . $testPath->getPath() . '/' . $this->getFileName() . '.php';
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

    private function getStubName(): string
    {
        if ($this->option('feature')) {
            return '/feature-test.stub';
        }

        return '/unit-test.stub';
    }
}
