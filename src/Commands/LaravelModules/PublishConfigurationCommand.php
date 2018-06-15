<?php

namespace Rawilk\LaravelModules\Commands\LaravelModules;

use Illuminate\Console\Command;

class PublishConfigurationCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'module:publish-config
							{module? : The name of the module to publish configuration files for}
							{--f|force : Force the configuration files to publish}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = "Publish a module's config files to the application";

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		if ($module = $this->argument('module')) {
			return $this->publishConfiguration($module);
		}

		$this->publishAll();
	}

	/**
	 * Publish config files for all enabled modules.
	 */
	private function publishAll()
	{
		foreach ($this->laravel['modules']->allEnabled() as $module) {
			$this->publishConfiguration($module->getName());
		}
	}

	/**
	 * Publish the given module's config files.
	 *
	 * @param string $module
	 */
	private function publishConfiguration($module)
	{
		$this->call('vendor:publish', [
			'--provider' => $this->getServiceProviderForModule($module),
			'--force'    => $this->option('force'),
			'--tag'      => ['config']
		]);
	}

	/**
	 * Get the given module's service provider.
	 *
	 * @param string $module
	 * @return string
	 */
	private function getServiceProviderForModule($module)
	{
		$namespace = $this->laravel['config']->get('modules.namespace');

		$studlyName = studly_case($module);

		return "{$namespace}\\{$studlyName}\\Providers\\{$studlyName}ServiceProvider";
	}
}
