<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Illuminate\Console\Command;
use Rawilk\LaravelModules\Exceptions\FileAlreadyExistsException;
use Rawilk\LaravelModules\Generators\FileGenerator;

abstract class GeneratorCommand extends Command
{
    /**
     * The name of 'name' argument.
     *
     * @var string
     */
    protected $argumentName = '';

    /**
     * Get the template contents.
     *
     * @return string
     */
    abstract protected function getTemplateContents();

    /**
     * Get the destination file path.
     *
     * @return string
     */
    abstract protected function getDestinationFilePath();

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = str_replace('\\', '/', $this->getDestinationFilePath());

        if (! $this->laravel['files']->isDirectory($dir = dirname($path))) {
            $this->laravel['files']->makeDirectory($dir, 0777, true);
        }

        $contents = $this->getTemplateContents();

        try {
            with(new FileGenerator($path, $contents))->generate();
            $this->info("Created: {$path}");
        } catch (FileAlreadyExistsException $e) {
            $this->error("File: {$path} already exists.");
        }
    }

    /**
     * Get class name.
     *
     * @return string
     */
    public function getClass()
    {
        return class_basename($this->argument($this->argumentName));
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace() : string
    {
        return '';
    }

    /**
     * Get the base class to extend.
     *
     * @param string $classType
     * @param bool $basename
     * @return string
     */
    protected function getBaseClass($classType, $basename = false)
    {
        $baseClass = $this->hasOption('base_class') && ! is_null($this->option('base_class'))
            ? $this->option('base_class')
            : $this->laravel['modules']->config("base_classes.{$classType}");

        return $basename ? class_basename($baseClass) : str_replace('/', '\\', $baseClass);
    }

    /**
     * Get the class namespace for the given module.
     *
     * @param \Rawilk\LaravelModules\Module $module
     * @return string
     */
    public function getClassNamespace($module)
    {
        $namespace = $this->laravel['modules']->config('namespace');

        $namespace .= '\\' . $module->getStudlyName();

        $namespace .= '\\' . $this->getDefaultNamespace();

        $namespace .= '\\' . $this->getExtraNamespace($this->argument($this->argumentName));

        $namespace = str_replace('/', '\\', $namespace);

        return trim($namespace, '\\');
    }

    private function getExtraNamespace($path)
    {
        $path = str_replace('/', "\\", $path);
        $pieces = explode("\\", $path);

        return join(array_slice($pieces, 0, count($pieces) - 1), "\\");
    }

    /**
     * Get the name of the file.
     *
     * @return string
     */
    protected function getFileName() : string
    {
        return studly_case($this->argument($this->argumentName));
    }
}
