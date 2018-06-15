<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\CanClearModulesCache;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class SeedMakeCommand extends GeneratorCommand
{
    use ModuleCommands, CanClearModulesCache;

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
    protected $signature = 'module:make-seed
                            {name : The name of the seeder to create}
                            {module? : The name of the module to create the seeder for}
                            {--m|master : Indicates the seeder is a master database seeder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new seeder for the specified module.';

    /**
     * Get the template contents.
     *
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/seeder.stub', [
            'NAME' => $this->getSeederName(),
            'MODULE' => $this->getModuleName(),
            'NAMESPACE' => $this->getClassNamespace($module),
        ]))->render();
    }

    /**
     * Get the destination file path.
     *
     * @return string
     */
    protected function getDestinationFilePath()
    {
        $this->clearCache();

        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $seederPath = GenerateConfigReader::read('seeder');

        return $path . $seederPath->getPath() . '/' . $this->getSeederName() . '.php';
    }

    /**
     * Get seeder name.
     *
     * @return string
     */
    private function getSeederName()
    {
        $end = $this->option('master') ? 'DatabaseSeeder' : 'TableSeeder';

        return studly_case($this->argument($this->argumentName)) . $end;
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace() : string
    {
        return $this->laravel['modules']->config('paths.generator.seeder.path', 'Database/Seeders');
    }
}
