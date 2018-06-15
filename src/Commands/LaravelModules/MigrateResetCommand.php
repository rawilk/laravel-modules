<?php

namespace Rawilk\LaravelModules\Commands\LaravelModules;

use Illuminate\Console\Command;
use Rawilk\LaravelModules\Migrations\Migrator;
use Rawilk\LaravelModules\Traits\LoadsMigrations;

class MigrateResetCommand extends Command
{
	use LoadsMigrations;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'module:migrate-reset
							{module? : The name of the module to reset migrations for}
							{--d|direction=desc : The direction to load the modules in}
							{--database= : The database connection to use}
							{--f|force : Force the operation to run when in production}
							{--p|pretend : Dump the SQL queries that would be run}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Reset migrations for the specified module';

	/**
	 * @var \Rawilk\LaravelModules\Repository
	 */
	protected $module;

	/**
	 * Execute the console command.
	 *
	 * @throws \Rawilk\LaravelModules\Exceptions\ModuleNotFoundException
	 */
	public function handle()
	{
		$this->module = $this->laravel['modules'];

		$name = $this->argument('module');

		if (! empty($name)) {
			return $this->reset($name);
		}

		$this->resetAll();
	}

	/**
	 * Reset all module migrations.
	 *
	 * @throws \Rawilk\LaravelModules\Exceptions\ModuleNotFoundException
	 */
	private function resetAll()
	{
		foreach ($this->module->getOrdered($this->option('direction')) as $module) {
			$this->info("Running for module: {$module->getName()}");

			$this->reset($module);
		}
	}

	/**
	 * Reset migrations for the given module.
	 *
	 * @param string|\Rawilk\LaravelModules\Module $module
	 * @throws \Rawilk\LaravelModules\Exceptions\ModuleNotFoundException
	 */
	private function reset($module)
	{
		if (is_string($module)) {
			$module = $this->module->findOrFail($module);
		}

		$migrator = new Migrator($module);

		$database = $this->option('database');

		if (! empty($database)) {
			$migrator->setDatabase($database);
		}

		$migrated = $migrator->reset();

		if (count($migrated)) {
			foreach ($migrated as $migration) {
				$this->info("Migration rolled back: {$migration}");
			}

			return;
		}

		$this->comment('Nothing to rollback.');
	}
}
