<?php

namespace Rawilk\LaravelModules\Commands\LaravelModules;

use Illuminate\Console\Command;

class EnableCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'module:enable
							{module : The module to enable}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Enable the specified module';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$module = $this->laravel['modules']->findOrFail($this->argument('module'));

		if ($module->disabled()) {
			$module->enable();

			$this->info("Module [{$module->getName()}] was enabled");
		} else {
			$this->comment("Module [{$module->getName()}] is already enabled");
		}
	}
}
