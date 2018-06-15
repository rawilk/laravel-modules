<?php

namespace Rawilk\LaravelModules\Commands\LaravelModules;

use Illuminate\Console\Command;

class DumpCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'module:dump
							{module? : The module to dump-autoload for}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Dump-autoload for the specified module or all modules';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$this->info('Generating optimized autoload modules');

		if ($module = $this->argument('module')) {
			return $this->dump($module);
		}

		$this->dumpAll();
	}

	/**
	 * Dump-autoload all modules.
	 */
	private function dumpAll()
	{
		foreach ($this->laravel['modules']->all() as $module) {
			$this->dump($module->getStudlyName());
		}
	}

	/**
	 * Dump-autoload for the given module.
	 *
	 * @param $module
	 */
	private function dump($module)
	{
		$module = $this->laravel['modules']->findOrFail($module);

		$this->comment("Running dump-autoload for module: {$module->getName()}");

		chdir($module->getPath());

		passthru('composer dump -o -n -q');
	}
}
