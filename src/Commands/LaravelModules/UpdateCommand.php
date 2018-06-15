<?php

namespace Rawilk\LaravelModules\Commands\LaravelModules;

use Illuminate\Console\Command;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class UpdateCommand extends Command
{
	use ModuleCommands;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'module:update
							{module? : The name of the module to update}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update dependencies for the specified module or for all modules';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$this->laravel['modules']->update($name = $this->getModuleName());

		$this->info("Module [{$name}] updated successfully");
	}
}
