<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Illuminate\Support\Str;
use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class CommandMakeCommand extends GeneratorCommand
{
    use ModuleCommands;

    /** @var string */
    protected $argumentName = 'name';

    /** @var string */
    protected $signature = 'module:make-command
                            {name : The name of the command}
                            {module? : The name of the module to create the command for}
                            {--signature= : The signature of the terminal command}
                            {--argument=* : Any arguments the command should accept}
                            {--options=* : Any options the command should accept}
                            {--description= : The console command description}';

    /** @var string */
    protected $description = 'Generate a new artisan command for the specified module.';

    protected function getDefaultNamespace(): string
    {
        /** @var \Rawilk\LaravelModules\Contracts\Repository $module */
        $module = $this->laravel['modules'];

        return $module->config('paths.generator.command.namespace') ?: $module->config('paths.generator.command.path', 'Console');
    }

    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $commandPath = GenerateConfigReader::read('command');

        return $path . $commandPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    protected function getTemplateContents(): string
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/command.stub', [
            'NAMESPACE'    => $this->getClassNamespace($module),
            'CLASS'        => $this->getClass(),
            'DESCRIPTION'  => $this->getCommandDescription(),
            'SIGNATURE'    => $this->getSignature(),
        ]))->render();
    }

    private function getCommandDescription(): string
    {
        return $this->option('description') ?: 'Command description';
    }

    private function getSignature(): string
    {
        $signature = $this->option('signature') ?: 'command:name';

        foreach ($this->option('argument') as $argument) {
            $signature .= "\n\t\t\t\t\t\t\t {{$argument}}";
        }

        foreach ($this->option('options') as $option) {
            $signature .= "\n\t\t\t\t\t\t\t {--" . str_replace(['-', '--'], '', $option) . '}';
        }

        return $signature;
    }
}
