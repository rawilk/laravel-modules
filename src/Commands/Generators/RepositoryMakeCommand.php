<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class RepositoryMakeCommand extends GeneratorCommand
{
    use ModuleCommands;

    /**
     * The name of the argument being used.
     *
     * @var string
     */
    protected $argumentName = 'repository';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-repository
                            {repository : The name of the repository class}
                            {module? : The name of the module to make the repository for}
                            {--base_class= : Override the default base repository class}
                            {--model= : The model the repository is for}
                            {--not_found_message= : The 404 message to output for exceptions}
                            {--p|plain : Generate a plain repository}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new repository for the specified module.';

    /**
     * Get the template contents.
     *
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'NAMESPACE'         => $this->getClassNamespace($module),
            'CLASS'             => class_basename($this->getRepositoryName()),
            'BASE_CLASS'        => $this->getBaseClass('repository'),
            'BASE_CLASS_SHORT'  => $this->getBaseClass('repository', true),
            'MODEL'             => $this->getModel(),
            'MODEL_NAMESPACE'   => $this->getModel(false),
            'NOT_FOUND_MESSAGE' => $this->option('not_found_message')
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

        $repositoryPath = GenerateConfigReader::read('repository');

        return $path . $repositoryPath->getPath() . '/' . $this->getRepositoryName() . '.php';
    }

    /**
     * Get the repository name.
     *
     * @return string
     */
    protected function getRepositoryName()
    {
        $repository = studly_case($this->argument('repository'));

        if (str_contains(strtolower($repository), 'repository') === false) {
            $repository .= 'Repository';
        }

        return $repository;
    }

    /**
     * Get the model argument.
     *
     * @param bool $classBasename
     * @return string
     */
    protected function getModel($classBasename = true)
    {
        $model = studly_case($this->option('model'));

        if (! $model) {
            $model = 'Illuminate\\Database\\Eloquent\\Model';
        }

        $model = str_replace('/', '\\', $model);

        return $classBasename
            ? class_basename($model) . '::class'
            : $model;
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace() : string
    {
        return $this->laravel['modules']->config('paths.generator.repository.path', 'Repositories');
    }

    /**
     * Get the stub file name based on options given.
     *
     * @return string
     */
    private function getStubName()
    {
        if ($this->option('plain')) {
            return '/repository-plain.stub';
        }

        $message = $this->option('not_found_message');
        if ($message && strlen($message)) {
            return '/repository-with-message.stub';
        }

        return '/repository.stub';
    }
}
