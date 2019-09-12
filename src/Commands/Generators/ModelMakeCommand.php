<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Illuminate\Support\Str;
use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class ModelMakeCommand extends GeneratorCommand
{
    use ModuleCommands;

    /** @var string */
    protected $argumentName = 'model';

    /** @var string */
    protected $signature = 'module:make-model
                            {model : The name of the model}
                            {module? : The name of the module to create the model for}
                            {--fillable= : The fillable attributes}
                            {--base_class= : Override the default base model class (from config)}
                            {--table= : The name of the database table}
                            {--m|migration : Create a migration for the model as well}';

    /** @var string */
    protected $description = 'Create a new model for the specified module.';

    public function handle(): void
    {
        parent::handle();

        $this->handleOptionalMigration();
    }

    protected function getDefaultNamespace(): string
    {
        /** @var \Rawilk\LaravelModules\Contracts\Repository $module */
        $module = $this->laravel['modules'];

        return $module->config('paths.generator.model.namespace') ?: $module->config('paths.generator.model.path', 'Models');
    }

    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $modelPath = GenerateConfigReader::read('model');

        return $path . $modelPath->getPath() . '/' . $this->getModelName() . '.php';
    }

    protected function getTemplateContents(): string
    {
        /** @var \Rawilk\LaravelModules\Module $module */
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/model.stub', [
            'FILLABLE'         => $this->getFillable(),
            'NAME'             => $this->getModelName(),
            'NAMESPACE'        => $this->getClassNamespace($module),
            'CLASS'            => $this->getClass(),
            'LOWER_NAME'       => $module->getLowerName(),
            'STUDLY_NAME'      => $module->getStudlyName(),
            'MODULE_NAMESPACE' => $this->laravel['modules']->config('namespace'),
            'TABLE'            => $this->createMigrationName(),
            'BASE_CLASS'       => $this->getBaseClass('model'),
            'BASE_CLASS_SHORT' => $this->getBaseClass('model', true),
        ]))->render();
    }

    private function createMigrationName(): string
    {
        if ($table = $this->option('table')) {
            return $table;
        }

        $pieces = preg_split(
            '/(?=[A-Z])/',
            class_basename($this->argument('model')),
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        $name = '';
        $count = count($pieces);

        foreach ($pieces as $index => $piece) {
            if ($index + 1 < $count) {
                $name .= strtolower($piece) . '_';
            } else {
                $name .= Str::plural(strtolower($piece));
            }
        }

        return $name;
    }

    private function getFillable(): string
    {
        $fillable = $this->option('fillable');

        if ($fillable !== null) {
            return str_replace(['"', ','], ["'", ', '], json_encode(explode(',', $fillable)));
        }

        return '[]';
    }

    private function getModelName(): string
    {
        return Str::studly($this->argument($this->argumentName));
    }

    private function handleOptionalMigration(): void
    {
        if ($this->option('migration')) {
            $migrationName = 'create_' . $this->createMigrationName() . '_table';

            $this->call('module:make-migration', [
                'name'   => $migrationName,
                'module' => $this->argument('module')
            ]);
        }
    }
}
