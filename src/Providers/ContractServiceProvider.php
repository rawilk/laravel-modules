<?php

namespace Rawilk\LaravelModules\Providers;

use Illuminate\Support\ServiceProvider;
use Rawilk\LaravelModules\Contracts\RepositoryInterface;
use Rawilk\LaravelModules\Repository;

class ContractServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(RepositoryInterface::class, Repository::class);
	}
}
