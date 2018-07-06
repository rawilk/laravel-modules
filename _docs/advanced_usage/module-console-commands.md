---
title: Module Console Commands
permalink: /docs/advanced-usage/module-console-commands/
doc_section_id: advanced-usage
---

A module may contain custom console commands. You can generate these commands manually, or with the
following artisan command:

```bash
php artisan module:make-command CreatePostCommand Blog
```

This will create a command called `CreatePostCommand` inside the `Blog` module. By default, this will be
in the path: `Modules/Blog/Console/CreatePostCommand`.

## Registering The Command

You cna register the command with the laravel method `commands` which is available inside a service provider class.

```php?start_inline=true
// In your boot or register method in the service provider:

public function boot()
{
    if ($this->app->runningInConsole()) {
        $this->commands([
            \Modules\Blog\Console\CreatePostCommand::class,
        ]);
    }
}
```

You can now access your command via `php artisan` in the console.

For more information on artisan commands, please refer to the [documentation](https://laravel.com/docs/5.5/artisan).