---
title: Facade Methods
permalink: /docs/advanced-usage/facade-methods/
doc_section_id: advanced-usage
---

There are many methods available for you to use to manage modules from the **Module** facade.

Get all modules:

```php?start_inline=true
Module::all();
```

Get all cached modules:

```php?start_inline=true
Module::getCached();
```

Get modules ordered by the `priority` key in each `module.json` file:

```php?start_inline=true
Module::getOrdered();
```

Get scanned modules:

```php?start_inline=true
Module::scan();
```

Find a specific module:

```php?start_inline=true
Module::find('name');

// Or

Module::get('name');
```

Find a module by name, and if it isn't found, throw the `Rawilk\LaravelModules\Exceptions\ModuleNotFoundException`:

```php?start_inline=true
Module::findOrFail('name');
```

Get scanned module paths:

```php?start_inline=true
Module::getScanPaths();
```

Get all modules as a collection instance:

```php?start_inline=true
Module::toCollection();
```

Get modules by status; `1` for active and `0` for inactive:

```php?start_inline=true
Module::getByStatus(1);
```

Check if a given module exists:

```php?start_inline=true
Module::has('name');
```

Get all enabled modules:

```php?start_inline=true
Module::allEnabled();
```

Get all disabled modules:

```php?start_inline=true
Module::allDisabled();
```

Get a count of all modules:

```php?start_inline=true
Module::count();
```

Get module path:

```php?start_inline=true
Module::getPath();
```

Register the modules:

```php?start_inline=true
Module::register();
```

Boot all available modules:

```php?start_inline=true
Module::boot();
```

Get all enabled modules as a collection instance:

```php?start_inline=true
Module::collections();
```

Get the module path from a given module:

```php?start_inline=true
Module::getModulePath('name');
```

Get the assets path for a given module:

```php?start_inline=true
Module::assetPath('name');
```

Get a config value from the `laravel-modules` package:

```php?start_inline=true
Module::config('composer.vendor');
```

Get the used storage path:

```php?start_inline=true
Module::getUsedStoragePath();
```

Get used module for cli session:

```php?start_inline=true
Module::getUsedNow();

// Or

Module::getUsed();
```

Set the used module for the cli session:

```php?start_inline=true
Module::setUsed('name');
```

Get module's assets path:

```php?start_inline=true
Module::getAssetsPath();
```

Get an asset url from a given module:

```php?start_inline=true
Module::asset('blog::img/logo.png');
```

Install a given module by name:

```php?start_inline=true
Module::install('rawilk/blog');
```

Update dependencies for a given module:

```php?start_inline=true
Module::update('name');
```

Add a macro to the module repository:

```php?start_inline=true
Module::macro('hello', function () {
    echo 'I am a macro';
});
```

Call a macro from the module repository:

```php?start_inline=true
Module::hello();
```

Get all required modules for a given module:

```php?start_inline=true
Module::getRequirements('name');
```