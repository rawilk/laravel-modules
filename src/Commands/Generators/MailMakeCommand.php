<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class MailMakeCommand extends GeneratorCommand
{
    use ModuleCommands;

    /** @var string */
    protected $argumentName = 'name';

    /** @var string */
    protected $signature = 'module:make-mail
                            {name : The name of the mailable}
                            {module? : The name of the module to make the mailable for}
                            {--base_class= : Override the default base mail class (from config)}';

    /** @var string */
    protected $description = 'Create a new mailable class for the specified module.';

    protected function getDefaultNamespace(): string
    {
        /** @var \Rawilk\LaravelModules\Contracts\Repository $module */
        $module = $this->laravel['modules'];

        return $module->config('paths.generator.emails.namespace') ?: $module->config('paths.generator.emails.path', 'Mail');
    }

    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $mailPath = GenerateConfigReader::read('emails');

        return $path . $mailPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    protected function getTemplateContents(): string
    {
        /** @var \Rawilk\LaravelModules\Module $module */
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/mail.stub', [
            'NAMESPACE'        => $this->getClassNamespace($module),
            'CLASS'            => $this->getClass(),
            'BASE_CLASS'       => $this->getBaseClass('mail'),
            'BASE_CLASS_SHORT' => $this->getBaseClass('mail', true),
        ]))->render();
    }
}
