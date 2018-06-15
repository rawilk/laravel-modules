<?php

namespace Rawilk\LaravelModules\Commands\LaravelModules;

use Illuminate\Console\Command;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Rawilk\LaravelModules\Module;
use Rawilk\LaravelModules\Repository;
use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Traits\ModuleCommands;
use RuntimeException;

class SeedCommand extends Command
{
	use ModuleCommands;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'module:seed
							{module? : The name of the module to run seeders for}
							{--class= : The class name of the root seeder}
							{--database= : The database connection to seed}
							{--f|force : Force the operation to run when in production}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run the database seeder for the specified module or all modules';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		try {
		    if ($name = $this->argument('module')) {
		    	$name = studly_case($name);

		    	$this->moduleSeed($this->getModuleByName($name));
		    } else {
		    	$modules = $this->getModuleRepository()->getOrdered();

		    	array_walk($modules, [$this, 'moduleSeed']);

		    	$this->info('All modules were seeded');
		    }
		} catch (\Throwable $e) {
			$this->reportException($e);
			$this->renderException($this->getOutput(), $e);

			return 1;
		}
	}

	/**
	 * Get the module repository.
	 *
	 * @return \Rawilk\LaravelModules\Repository
	 * @throws RuntimeException
	 */
	public function getModuleRepository()
	{
		$modules = $this->laravel['modules'];

		if (! $modules instanceof Repository) {
			throw new RuntimeException('Module repository not found!');
		}

		return $modules;
	}

	/**
	 * Get a module by name.
	 *
	 * @param string $name
	 * @return \Rawilk\LaravelModules\Module
	 * @throws RuntimeException
	 */
	public function getModuleByName($name)
	{
		$modules = $this->getModuleRepository();

		if (! $modules->has($name)) {
			throw new RuntimeException("Module [{$name}] does not exist!");
		}

		return $modules->find($name);
	}

	/**
	 * Seed the given module.
	 *
	 * @param \Rawilk\LaravelModules\Module $module
	 */
	public function moduleSeed(Module $module)
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
		} else {
			$class = $this->getSeederName($name);

			if (class_exists($class)) {
				$seeders[] = $class;
			}
		}

		if (count($seeders) > 0) {
			array_walk($seeders, [$this, 'dbSeed']);

			$this->info("Module [{$name}] was seeded");
		}
	}

	/**
	 * Seed the given module.
	 *
	 * @param $className
	 */
	protected function dbSeed($className)
	{
		$params = [
			'--class' => $className,
		];

		if ($option = $this->option('database')) {
			$params['--database'] = $option;
		}

		if ($option = $this->option('force')) {
			$params['--force'] = $option;
		}

		$this->call('db:seed', $params);
	}

	/**
	 * Get the master database seeder for the specified module.
	 *
	 * @param string $name
	 * @return string
	 */
	private function getSeederName($name)
	{
		$name = studly_case($name);

		$namespace = $this->laravel['modules']->config('namespace');

		$seederPath = GenerateConfigReader::read('seeder');
		$seederPath = str_replace('/', '\\', $seederPath->getPath());

		return $namespace . '\\' . $name . '\\' . $seederPath . '\\' . $name . 'DatabaseSeeder';
	}

	/**
	 * Render the exception to the console.
	 *
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 * @param \Throwable $e
	 */
	protected function renderException($output, \Throwable $e)
	{
		$this->laravel[ExceptionHandler::class]->renderForConsole($output, $e);
	}

	/**
	 * Report the exception to the exception handler.
	 *
	 * @param \Throwable $e
	 */
	protected function reportException(\Throwable $e)
	{
		$this->laravel[ExceptionHandler::class]->report($e);
	}
}
