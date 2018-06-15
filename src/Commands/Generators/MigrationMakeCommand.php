<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Migrations\NameParser;
use Rawilk\LaravelModules\Support\Migrations\SchemaParser;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class MigrationMakeCommand extends GeneratorCommand
{
    use ModuleCommands;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-migration
                            {name : The name of the migration}
                            {module? : The name of the module to make the migration for}
                            {--fields= : The fields to migrate}
                            {--p|plain : Create a plain migration}';

    /**
     * Get the schema parser.
     *
     * @return \Rawilk\LaravelModules\Support\Migrations\SchemaParser
     */
    public function getSchemaParser()
    {
        return new SchemaParser($this->option('fields'));
    }

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration for the specified module.';

    /**
     * Get the template contents.
     *
     * @return string
     */
    protected function getTemplateContents()
    {
        $parser = new NameParser($this->argument('name'));

        $commonReplacements = [
            'class' => $this->getClass(),
            'table' => $parser->getTableName()
        ];

        if ($parser->isCreate()) {
            return Stub::create('/migration/create.stub', array_merge($commonReplacements, [
                'fields' => $this->getSchemaParser()->render()
            ]));
        }

        if ($parser->isAdd()) {
            return Stub::create('/migration/add.stub', array_merge($commonReplacements, [
                'fields_up'   => $this->getSchemaParser()->up(),
                'fields_down' => $this->getSchemaParser()->down()
            ]));
        }

        if ($parser->isDelete()) {
            return Stub::create('/migration/delete.stub', array_merge($commonReplacements, [
                'fields_up'   => $this->getSchemaParser()->up(),
                'fields_down' => $this->getSchemaParser()->down()
            ]));
        }

        if ($parser->isDrop()) {
            return Stub::create('/migration/drop.stub', array_merge($commonReplacements, [
                'fields' => $this->getSchemaParser()->render()
            ]));
        }

        return Stub::create('/migration/plain.stub', $commonReplacements);
    }

    /**
     * Get the destination file path.
     *
     * @return string
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $migrationPath = GenerateConfigReader::read('migration');

        return $path . $migrationPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * Get the name of the file.
     *
     * @return string
     */
    protected function getFileName() : string
    {
        return date('Y_m_d_His_') . $this->getSchemaName();
    }

    /**
     * Get the schema name.
     *
     * @return array|string
     */
    private function getSchemaName()
    {
        return $this->argument('name');
    }

    /**
     * Get the class name in studly case.
     *
     * @return string
     */
    private function getClassName()
    {
        return studly_case($this->argument('name'));
    }

    /**
     * Get class name.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->getClassName();
    }

    /**
     * Run the command.
     */
    public function handle()
    {
        parent::handle();

        if (app()->environment() === 'testing') {
            return;
        }
    }
}
