<?php

namespace Rawilk\LaravelModules\Commands\LaravelModules;

use Illuminate\Console\Command;
use Rawilk\LaravelModules\Json;
use Rawilk\LaravelModules\Process\Installer;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:install
                            {name? : The name of the module to install}
                            {version? : The version of the module to install}
                            {--timeout= : The process timeout}
                            {--p|path= : The installation path}
                            {--type= : The type of installation}
                            {--t|tree : Install the module as a git subtree}
                            {--no|no-update : Disables the automatic update of dependencies}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the specified module by the specified package name (vendor/name)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (is_null($this->argument('name'))) {
            return $this->installFromFile();
        }

        $this->install(
            $this->argument('name'),
            $this->argument('version'),
            $this->option('type'),
            $this->option('tree')
        );
    }

    /**
     * Install modules from the modules.json file.
     */
    private function installFromFile()
    {
        if (! file_exists($path = base_path('modules.json'))) {
            return $this->error("File 'modules.json' does not exist in your project root");
        }

        $modules = Json::make($path);

        $dependencies = $modules->get('require', []);

        foreach ($dependencies as $module) {
            $module = collect($module);

            $this->install(
                $module->get('name'),
                $module->get('version'),
                $module->get('type')
            );
        }
    }

    /**
     * Install the given module.
     *
     * @param string $name
     * @param string $version
     * @param string $type
     * @param bool $tree
     */
    private function install($name, $version = 'dev-master', $type = 'composer', $tree = false)
    {
        $installer = new Installer(
            $name,
            $version,
            $type ?: $this->option('type'),
            $tree ?: $this->option('tree')
        );

        $installer->setRepository($this->laravel['modules'])
            ->setConsole($this);

        if ($timeout = $this->option('timeout')) {
            $installer->setTimeout($timeout);
        }

        if ($path = $this->option('path')) {
            $installer->setPath($path);
        }

        $installer->run();

        if (! $this->option('no-update')) {
            $this->call('module:update', [
                'module' => $installer->getModuleName(),
            ]);
        }
    }
}
