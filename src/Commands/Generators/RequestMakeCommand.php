<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class RequestMakeCommand extends GeneratorCommand
{
    use ModuleCommands;

    /**
     * The name of 'name' argument.
     *
     * @var string
     */
    protected $argumentName = 'name';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-request
                            {name : The name of the form request class}
                            {module? : The name of the module to create the request for}
                            {--base_class= : Override the default base class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form request class for the specified module';

    /**
     * Get the template contents.
     *
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/request.stub', [
            'NAMESPACE'        => $this->getClassNamespace($module),
            'CLASS'            => $this->getClass(),
            'BASE_CLASS'       => $this->getBaseClass('request'),
            'BASE_CLASS_SHORT' => $this->getBaseClass('request', true)
        ]))->render();
    }

    /**
     * Get the destination file path.
     *
     * @return string
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $requestPath = GenerateConfigReader::read('request');

        return $path . $requestPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace() : string
    {
        return $this->laravel['modules']->config('paths.generator.request.path', 'Http/Requests');
    }
}
