<?php

namespace Rawilk\LaravelModules\Commands\Other;

use Illuminate\Console\Command;

class SetupCommand extends Command
{
    /** @var string */
    protected $signature = 'module:setup';

    /** @var string */
    protected $description = 'Setup module folders for first use.';

    public function handle(): void
    {
        $this->generateModulesFolder();

        $this->generateAssetsFolder();
    }

    private function generateAssetsFolder(): void
    {
        $this->generateDirectory(
            $this->laravel['modules']->config('paths.assets'),
            'Assets directory created successfully!',
            'Assets directory already exists!'
        );
    }

    private function generateDirectory(string $dir, string $success, string $error): void
    {
        if (! $this->laravel['files']->isDirectory($dir)) {
            $this->laravel['files']->makeDirectory($dir, 0755, true, true);

            $this->info($success);

            return;
        }

        $this->error($error);
    }

    private function generateModulesFolder(): void
    {
        $this->generateDirectory(
            $this->laravel['modules']->config('paths.modules'),
            'Modules directory created successfully!',
            'Modules directory already exists!'
        );
    }
}
