<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Illuminate\Support\Str;
use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Migrations\NameParser;
use Rawilk\LaravelModules\Support\Migrations\SchemaParser;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class MigrationMakeCommand extends GeneratorCommand
{
    use ModuleCommands;

    /** @var string */
    protected $argumentName = 'name';

    /** @var string */
    protected $signature = 'module:make-migration
                            {name : The name of the migration}
                            {module? : The name of the module to create the migration for}
                            {--fields= : The fields to migrate}
                            {--p|plain : Create a plain migration}';

    /** @var string */
    protected $description = 'Create a new migration for the specified module.';

    protected function getClass(): string
    {
        return Str::studly($this->argument('name'));
    }

    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $generatorPath = GenerateConfigReader::read('migration');

        return $path . $generatorPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    protected function getFileName(): string
    {
        return date('Y_m_d_His_') . $this->getSchemaName();
    }

    protected function getTemplateContents(): string
    {
        $parser = new NameParser($this->argument('name'));

        if ($parser->isCreate()) {
            return Stub::create('/migration/create.stub', [
                'class'  => $this->getClass(),
                'table'  => $parser->getTableName(),
                'fields' => $this->getSchemaParser()->render()
            ]);
        }

        if ($parser->isAdd()) {
            return Stub::create('/migration/add.stub', [
                'class'       => $this->getClass(),
                'table'       => $parser->getTableName(),
                'fields_up'   => $this->getSchemaParser()->up(),
                'fields_down' => $this->getSchemaParser()->down()
            ]);
        }

        if ($parser->isDelete()) {
            return Stub::create('/migration/delete.stub', [
                'class'       => $this->getClass(),
                'table'       => $parser->getTableName(),
                'fields_down' => $this->getSchemaParser()->up(),
                'fields_up'   => $this->getSchemaParser()->down()
            ]);
        }

        if ($parser->isDrop()) {
            return Stub::create('/migration/drop.stub', [
                'class'  => $this->getClass(),
                'table'  => $parser->getTableName(),
                'fields' => $this->getSchemaParser()->render()
            ]);
        }

        return Stub::create('/migration/plain.stub', [
            'class' => $this->getClass()
        ]);
    }

    private function getClassName(): string
    {
        return Str::studly($this->argument('name'));
    }

    private function getSchemaName(): string
    {
        return $this->argument('name');
    }

    private function getSchemaParser(): SchemaParser
    {
        return new SchemaParser($this->option('fields'));
    }
}
