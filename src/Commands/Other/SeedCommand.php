<?php

namespace Rawilk\LaravelModules\Commands\Other;

use Illuminate\Console\Command;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Str;
use Rawilk\LaravelModules\Contracts\Repository;
use Rawilk\LaravelModules\Module;
use RuntimeException;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class SeedCommand extends Command
{
    /** @var string */
    protected $signature = 'module:seed
                            {module? : The name of the module to seed}
                            {--class= : Specify a specific seeder class to run}
                            {--database= : The database connection to seed}
                            {--force : Force the operation to run when in production}';

    /** @var string */
    protected $description = 'Run database seeds for a specific module or all modules.';

    public function handle(): ?int
    {
        try {
            if ($name = $this->argument('module')) {
                $name = Str::studly($name);

                $this->moduleSeed($this->getModuleByName($name));
            } else {
                $modules = $this->getModuleRepository()->getOrdered();

                array_walk($modules, [$this, 'moduleSeed']);

                $this->info('All modules seeded.');
            }
        } catch (Throwable $e) {
            $this->reportException($e);

            $this->renderException($this->getOutput(), $e);

            return 1;
        }

        return 0;
    }

    private function dbSeed(string $className): void
    {
        $class = $className;

        if ($seederClass = $this->option('class')) {
            $class = Str::finish(substr($className, 0, strrpos($className, '\\')), '\\') . $seederClass;
        }

        $params = ['--class' => $class];

        if ($database = $this->option('database')) {
            $params['--database'] = $database;
        }

        if ($force = $this->option('force')) {
            $params['--force'] = $force;
        }

        $this->call('db:seed', $params);
    }

    private function getModuleByName(string $name): Module
    {
        $modules = $this->getModuleRepository();

        if (! $modules->has($name)) {
            throw new RuntimeException("Module [{$name}] does not exist!");
        }

        return $modules->find($name);
    }

    private function getModuleRepository(): Repository
    {
        $modules = $this->laravel['modules'];

        if (! $modules instanceof Repository) {
            throw new RuntimeException('Module repository not found!');
        }

        return $modules;
    }

    private function moduleSeed(Module $module): void
    {
        $seeders = [];
        $name = $module->getName();
        $config = $module->get('migration');

        if (is_array($config) && array_key_exists('seeds', $config)) {
            foreach ((array) $config['seeds'] as $class) {
                if (class_exists($class)) {
                    $seeders[] = $class;
                }
            }
        }

        if (count($seeders) > 0) {
            array_walk($seeders, [$this, 'dbSeed']);

            $this->info("Module [{$name}] seeded.");
        }
    }

    private function renderException(OutputInterface $output, Throwable $e): void
    {
        $this->laravel[ExceptionHandler::class]->renderForConsole($output, $e);
    }

    private function reportException(Throwable $e): void
    {
        $this->laravel[ExceptionHandler::class]->report($e);
    }
}
