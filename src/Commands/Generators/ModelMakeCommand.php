<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class ModelMakeCommand extends GeneratorCommand
{
    use ModuleCommands;

    /**
     * The name of 'name' argument.
     *
     * @var string
     */
    protected $argumentName = 'model';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-model
                            {model : The name of the model}
                            {module? : The name of the module to create the model for}
                            {--fillable= : The fillable attributes}
                            {--base_class= : Override the default base class}
                            {--table= : The name of the database table}
                            {--m|migration : Create a migration for the model as well}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model for the specified module';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        $this->handleOptionalMigrationOption();
    }

    /**
     * Get the template contents.
     *
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/model.stub', [
            'FILLABLE'         => $this->getFillable(),
            'NAME'             => $this->getModelName(),
            'NAMESPACE'        => $this->getClassNamespace($module),
            'CLASS'            => $this->getClass(),
            'LOWER_NAME'       => $module->getLowerName(),
            'MODULE'           => $this->getModuleName(),
            'STUDLY_NAME'      => $module->getStudlyName(),
            'MODULE_NAMESPACE' => $this->laravel['modules']->config('namespace'),
            'TABLE'            => $this->createMigrationName(),
            'BASE_CLASS'       => $this->getBaseClass('model'),
            'BASE_CLASS_SHORT' => $this->getBaseClass('model', true),
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

        $modelPath = GenerateConfigReader::read('model');

        return $path . $modelPath->getPath() . '/' . $this->getModelName() . '.php';
    }

    /**
     * Create a proper migration name.
     *
     * @example ProductDetail => product_details
     * @example Product => products
     * @return string
     */
    private function createMigrationName()
    {
        if (! is_null($this->option('table'))) {
            return $this->option('table');
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
                $name .= str_plural(strtolower($piece));
            }
        }

        return $name;
    }

    /**
     * Create a migration for the model if specified.
     */
    private function handleOptionalMigrationOption()
    {
        if ($this->option('migration')) {
            $migrationName = 'create_' . $this->createMigrationName() . '_table';

            $this->call('module:make-migration', [
                'name'   => $migrationName,
                'module' => $this->argument('module')
            ]);
        }
    }

    /**
     * Get the model name.
     *
     * @return string
     */
    private function getModelName()
    {
        return studly_case($this->argument($this->argumentName));
    }

    /**
     * Get the fillable attributes.
     *
     * @return string
     */
    private function getFillable()
    {
        $fillable = $this->option('fillable');

        if (! is_null($fillable)) {
            $fillable = str_replace('"', "'", json_encode(explode(',', $fillable)));

            return str_replace(',', ', ', $fillable);
        }

        return '[]';
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace() : string
    {
        return $this->laravel['modules']->config('paths.generator.model.path', 'Models');
    }
}
