---
title: Facade Methods
permalink: /docs/advanced-usage/facade-methods/
doc_section_id: advanced-usage
---

There are many methods available for you to use to manage modules from the **Module** facade.

Get all modules:

```php
Module::all();
```

Get all cached modules:

```php
Module::getCached();
```

Get modules ordered by the `priority` key in each `module.json` file:

```php
Module::getOrdered();
```

Get scanned modules:

```php
Module::scan();
```

Find a specific module:

```php
Module::find('name');

// Or

Module::get('name');
```

Find a module by name, and if it isn't found, throw the `Rawilk\LaravelModules\Exceptions\ModuleNotFoundException`:

```php
Module::findOrFail('name');
```

Get scanned module paths:

```php
Module::getScanPaths();
```

Get all modules as a collection instance:

```php
Module::toCollection();
```

Get modules by status; `1` for active and `0` for inactive:

```php
Module::getByStatus(1);
```

Check if a given module exists:

```php
Module::has('name');
```

Get all enabled modules:

```php
Module::allEnabled();
```

Get all disabled modules:

```php
Module::allDisabled();
```

Get a count of all modules:

```php
Module::count();
```

Get module path:

```php
Module::getPath();
```

Register the modules:

```php
Module::register();
```

Boot all available modules:

```php
Module::boot();
```

Get all enabled modules as a collection instance:

```php
Module::collections();
```

Get the module path from a given module:

```php
Module::getModulePath('name');
```

Get the assets path for a given module:

```php
Module::assetPath('name');
```

Get a config value from the `laravel-modules` package:

```php
Module::config('composer.vendor');
```

Get the used storage path:

```php
Module::getUsedStoragePath();
```

Get used module for cli session:

```php
Module::getUsedNow();

// Or

Module::getUsed();
```

Set the used module for the cli session:

```php
Module::setUsed('name');
```

Get module's assets path:

```php
Module::getAssetsPath();
```

Get an asset url from a given module:

```php
Module::asset('blog::img/logo.png');
```

Install a given module by name:

```php
Module::install('rawilk/blog');
```

Update dependencies for a given module:

```php
Module::update('name');
```

Add a macro to the module repository:

```php
Module::macro('hello', function () {
    echo 'I am a macro';
});
```

Call a macro from the module repository:

```php
Module::hello();
```

Get all required modules for a given module:

```php
Module::getRequirements('name');
```