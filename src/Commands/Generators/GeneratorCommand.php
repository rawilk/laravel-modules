<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Rawilk\LaravelModules\Exceptions\FileAlreadyExists;
use Rawilk\LaravelModules\Generators\FileGenerator;
use Rawilk\LaravelModules\Module;

abstract class GeneratorCommand extends Command
{
    /**
     * The name of the 'name' argument.
     *
     * @var string
     */
    protected $argumentName = '';

    abstract protected function getDestinationFilePath(): string;

    abstract protected function getTemplateContents(): string;

    public function handle(): void
    {
        $path = str_replace('\\', '/', $this->getDestinationFilePath());

        if (! $this->laravel['files']->isDirectory($dir = dirname($path))) {
            $this->laravel['files']->makeDirectory($dir, 0777, true);
        }

        $contents = $this->getTemplateContents();

        try {
            $overwriteFile = $this->hasOption('force') ? $this->option('force') : false;
            (new FileGenerator($path, $contents))->withFileOverwrite($overwriteFile)->generate();

            $this->info("Created: {$path}");
        } catch (FileAlreadyExists $e) {
            $this->error("File: {$path} already exists.");
        }
    }

    /**
     * Get the base class to extend for a given class type.
     *
     * @param string $classType
     * @param bool $returnBasename
     * @return string
     */
    protected function getBaseClass(string $classType, bool $returnBasename = false): string
    {
        $baseClass = $this->hasOption('base_class') && $this->option('base_class') !== null
            ? $this->option('base_class')
            : $this->laravel['modules']->config("base_classes.{$classType}");

        return $returnBasename ? class_basename($baseClass) : str_replace('/', '\\', $baseClass);
    }

    protected function getClass(): string
    {
        return class_basename($this->argument($this->argumentName));
    }

    protected function getClassNamespace(Module $module): string
    {
        $namespace = $this->laravel['modules']->config('namespace');

        $namespace .= '\\' . $module->getStudlyName();

        $namespace .= '\\' . $this->getDefaultNamespace();

        $namespace .= '\\' . $this->getExtraNamespace($this->argument($this->argumentName));

        $namespace = str_replace('/', '\\', $namespace);

        return trim($namespace, '\\');
    }

    protected function getDefaultNamespace(): string
    {
        return '';
    }

    protected function getFileName(): string
    {
        return Str::studly($this->argument($this->argumentName));
    }

    private function getExtraNamespace(string $path): string
    {
        $path = str_replace('/', '\\', $path);
        $pieces = explode("\\", $path);

        return implode("\\", array_slice($pieces, 0, count($pieces) - 1) );
    }
}
