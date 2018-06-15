<?php

namespace Rawilk\LaravelModules\Commands\LaravelModules;

use Illuminate\Console\Command;

class UnUseCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'module:unuse';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Forget the used module used with module:use';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$this->laravel['modules']->forgetUsed();

		$this->info('No module is being used now');
	}
}
