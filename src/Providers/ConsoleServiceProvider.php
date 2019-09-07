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
use Rawilk\LaravelModules\Commands\Other\DisableCommand;
use Rawilk\LaravelModules\Commands\Other\DumpCommand;
use Rawilk\LaravelModules\Commands\Other\EnableCommand;
use Rawilk\LaravelModules\Commands\Other\InstallCommand;
use Rawilk\LaravelModules\Commands\Other\ListCommand;
use Rawilk\LaravelModules\Commands\Other\MigrateCommand;
use Rawilk\LaravelModules\Commands\Other\MigrateRefreshCommand;
use Rawilk\LaravelModules\Commands\Other\MigrateResetCommand;
use Rawilk\LaravelModules\Commands\Other\MigrateRollbackCommand;
use Rawilk\LaravelModules\Commands\Other\PublishCommand;
use Rawilk\LaravelModules\Commands\Other\PublishConfigurationCommand;
use Rawilk\LaravelModules\Commands\Other\PublishMigrationCommand;
use Rawilk\LaravelModules\Commands\Other\PublishTranslationCommand;
use Rawilk\LaravelModules\Commands\Other\SeedCommand;
use Rawilk\LaravelModules\Commands\Other\UnUseCommand;
use Rawilk\LaravelModules\Commands\Other\UpdateCommand;
use Rawilk\LaravelModules\Commands\Other\UseCommand;

class ConsoleServiceProvider extends ServiceProvider
{
    protected static $generatorCommands = [
        CommandMakeCommand::class,
        ControllerMakeCommand::class,
        EventMakeCommand::class,
        FactoryMakeCommand::class,
        JobMakeCommand::class,
        ListenerMakeCommand::class,
        MailMakeCommand::class,
        MiddlewareMakeCommand::class,
        MigrationMakeCommand::class,
        ModelMakeCommand::class,
        ModuleMakeCommand::class,
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

    protected static $otherCommands = [
        DisableCommand::class,
        DumpCommand::class,
        EnableCommand::class,
        InstallCommand::class,
        ListCommand::class,
        MigrateCommand::class,
        MigrateRefreshCommand::class,
        MigrateResetCommand::class,
        MigrateRollbackCommand::class,
        PublishCommand::class,
        PublishConfigurationCommand::class,
        PublishMigrationCommand::class,
        PublishTranslationCommand::class,
        SeedCommand::class,
        UnUseCommand::class,
        UpdateCommand::class,
        UseCommand::class,
    ];

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(static::$generatorCommands);
            $this->commands(static::$otherCommands);
        }
    }

    public function provides(): array
    {
        return array_merge(static::$generatorCommands, static::$otherCommands);
    }
}
