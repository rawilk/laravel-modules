<?php

namespace Rawilk\LaravelModules\Providers;

use Illuminate\Support\ServiceProvider;
use Rawilk\LaravelModules\Contracts\Repository;
use Rawilk\LaravelModules\Laravel\LaravelFileRepository;

class ContractServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(Repository::class, LaravelFileRepository::class);
    }
}
