<?php

namespace Rawilk\LaravelModules\Providers;

use Illuminate\Support\ServiceProvider;
use Rawilk\LaravelModules\Commands\Generators\CommandMakeCommand;
use Rawilk\LaravelModules\Commands\Generators\ControllerMakeCommand;
use Rawilk\LaravelModules\Commands\Generators\EventMakeCommand;
use Rawilk\LaravelModules\Commands\Generators\FactoryMakeCommand;
use Rawilk\LaravelModules\Commands\Generators\JobMakeCommand;
use Rawilk\LaravelModules\Commands\Generators\ListenerMakeCommand;
use Rawilk\LaravelModules\Commands\Generators\MailMakeCommand;
use Rawilk\LaravelModules\Commands\Generators\MiddlewareMakeCommand;
use Rawilk\LaravelModules\Commands\Generators\MigrationMakeCommand;
use Rawilk\LaravelModules\Commands\Generators\ModelMakeCommand;
use Rawilk\LaravelModules\Commands\Generators\ModuleMakeCommand;
use Rawilk\LaravelModules\Commands\Generators\PolicyMakeCommand;
use Rawilk\LaravelModules\Commands\Generators\ProviderMakeCommand;
use Rawilk\LaravelModules\Commands\Generators\RepositoryMakeCommand;
use Rawilk\LaravelModules\Commands\Generators\RequestMakeCommand;
use Rawilk\LaravelModules\Commands\Generators\ResourceMakeCommand;
use Rawilk\LaravelModules\Commands\Generators\RouteProviderMakeCommand;
use Rawilk\LaravelModules\Commands\Generators\RuleMakeCommand;
use Rawilk\LaravelModules\Commands\Generators\SeedMakeCommand;
use Rawilk\LaravelModules\Commands\Generators\TestMakeCommand;
use Rawilk\LaravelModules\Commands\LaravelModules\DisableCommand;
use Rawilk\LaravelModules\Commands\LaravelModules\DumpCommand;
use Rawilk\LaravelModules\Commands\LaravelModules\EnableCommand;
use Rawilk\LaravelModules\Commands\LaravelModules\InstallCommand;
use Rawilk\LaravelModules\Commands\LaravelModules\ListCommand;
use Rawilk\LaravelModules\Commands\LaravelModules\MigrateCommand;
use Rawilk\LaravelModules\Commands\LaravelModules\MigrateRefreshCommand;
use Rawilk\LaravelModules\Commands\LaravelModules\MigrateResetCommand;
use Rawilk\LaravelModules\Commands\LaravelModules\MigrateRollbackCommand;
use Rawilk\LaravelModules\Commands\LaravelModules\MigrateStatusCommand;
use Rawilk\LaravelModules\Commands\LaravelModules\PublishCommand;
use Rawilk\LaravelModules\Commands\LaravelModules\PublishConfigurationCommand;
use Rawilk\LaravelModules\Commands\LaravelModules\PublishMigrationCommand;
use Rawilk\LaravelModules\Commands\LaravelModules\PublishTranslationCommand;
use Rawilk\LaravelModules\Commands\LaravelModules\SeedCommand;
use Rawilk\LaravelModules\Commands\LaravelModules\SetupCommand;
use Rawilk\LaravelModules\Commands\LaravelModules\UnUseCommand;
use Rawilk\LaravelModules\Commands\LaravelModules\UpdateCommand;
use Rawilk\LaravelModules\Commands\LaravelModules\UseCommand;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * The generator commands to register.
     *
     * @var array
     */
    protected $generatorCommands = [
        CommandMakeCommand::class,
        ControllerMakeCommand::class,
        EventMakeCommand::class,
        FactoryMakeCommand::class,
        ListenerMakeCommand::class,
        ModuleMakeCommand::class,
        JobMakeCommand::class,
        MailMakeCommand::class,
        MiddlewareMakeCommand::class,
        MigrationMakeCommand::class,
        ModelMakeCommand::class,
        PolicyMakeCommand::class,
        ProviderMakeCommand::class,
        RepositoryMakeCommand::class,
        RequestMakeCommand::class,
        ResourceMakeCommand::class,
        RouteProviderMakeCommand::class,
        RuleMakeCommand::class,
        SeedMakeCommand::class,
        TestMakeCommand::class,
    ];

    /**
     * The laravel modules commands to register.
     *
     * @var array
     */
    protected $laravelModulesCommands = [
        DisableCommand::class,
        DumpCommand::class,
        EnableCommand::class,
        InstallCommand::class,
        ListCommand::class,
        MigrateCommand::class,
        MigrateRefreshCommand::class,
        MigrateResetCommand::class,
        MigrateRollbackCommand::class,
        MigrateStatusCommand::class,
        PublishCommand::class,
        PublishConfigurationCommand::class,
        PublishMigrationCommand::class,
        PublishTranslationCommand::class,
        SeedCommand::class,
        SetupCommand::class,
        UnUseCommand::class,
        UpdateCommand::class,
        UseCommand::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands($this->laravelModulesCommands);
            $this->commands($this->generatorCommands);
        }
    }
}
