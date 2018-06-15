<?php

namespace Rawilk\LaravelModules\Commands\LaravelModules;

use Illuminate\Console\Command;

class SetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up modules folders for first use';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->generateModulesFolder();

        $this->generateAssetsFolder();
    }

    /**
     * Generate the modules folder.
     */
    private function generateModulesFolder()
    {
        $this->generateDirectory(
            $this->laravel['modules']->config('paths.modules'),
            'Modules directory created successfully',
            'Modules directory already exists'
        );
    }

    /**
     * Generate the assets folder.
     */
    private function generateAssetsFolder()
    {
        $this->generateDirectory(
            $this->laravel['modules']->config('paths.assets'),
            'Assets directory created successfully',
            'Assets directory already exists'
        );
    }

    /**
     * Generate the given directory.
     *
     * @param string $dir
     * @param string $success
     * @param string $error
     */
    private function generateDirectory($dir, $success, $error)
    {
        if (! $this->laravel['files']->isDirectory($dir)) {
            $this->laravel['files']->makeDirectory($dir, 0755, true, true);

            return $this->info($success);
        }

        $this->error($error);
    }
}
