<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class MailMakeCommand extends GeneratorCommand
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
    protected $signature = 'module:make-mail
                            {name : The name of the mailable}
                            {module? : The name of the module to make the mailable for}
                            {--base_class= : Override the default base mail class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new mailer class for the specified module';

    /**
     * Get the template contents.
     *
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/mail.stub', [
            'NAMESPACE'        => $this->getClassNamespace($module),
            'CLASS'            => $this->getClass(),
            'BASE_CLASS'       => $this->getBaseClass('mail'),
            'BASE_CLASS_SHORT' => $this->getBaseClass('mail', true)
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

        $mailPath = GenerateConfigReader::read('emails');

        return $path . $mailPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace() : string
    {
        return $this->laravel['modules']->config('paths.generator.emails.path', 'Mail');
    }
}
