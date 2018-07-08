---
title: Installation and Setup
permalink: /docs/installation/
doc_section_id: docs
excerpt: Laravel-Modules installation and setup instructions.
---

## Composer

To install through Composer, run the following command:

```bash
composer require rawilk/laravel-modules
```

The package will automatically register a service provider and alias.

You can optionally publish the package's configuration file by running:

```bash
php artisan vendor:publish --provider="Rawilk\LaravelModules\LaravelModulesServiceProvider"
```

## Autoloading Modules

By default the modules are not loaded automatically. You can autoload the modules by using `psr-4` in your `composer.json`
file:

```yaml
{
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Modules\\": "Modules/"
        }
    }
}
```

- If you changed the module namespace in the [config file](https://github.com/rawilk/laravel-modules/blob/master/config/config.php#L12), be sure to change `"Modules\\"` to your new namespace.
- If you changed the directory where the modules get stored in the [config file](https://github.com/rawilk/laravel-modules/blob/master/config/config.php#L77), be sure to change `"Modules/"` to the directory they will be stored in.

<div class="alert alert-success">
    <div class="alert-content">
        <h4 class="alert-title">Tip:</h4>
        <p>
            Be sure to run <code>composer dump-autoload</code> afterwards.
        </p>
    </div>
</div>