# Laravel-Modules

`rawilk/laravel-modules` is a Laravel package to help build modular apps using modules.
A module is like a Laravel package; it has some views, controllers and models.
Laravel-Modules is supported and tested in Laravel 5.

This package is a re-published and slightly modified version of [nWidart/laravel-modules](https://github.com/nWidart/laravel-modules).
I mainly created my own version of this package because I wanted full control over how
modules are managed, and I also have my own needs as for what I need the package to do.

## Install

To install the package through composer, run the following command

```bash
composer require rawilk/laravel-modules
```

The package will automatically register its service provider and alias.

You can optionally publish the package's configuration file by running:

```bash
php artisan vendor:publish --provider="Rawilk\LaravelModules\LaravelModulesServiceProvider"
```

## Autoloading Modules

By default the modules are not loaded automatically. You can autoload the modules by using `psr-4` in your `composer.json` file:

```
{
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Modules\\": "Modules/"
        }
    }
}
```

- If you changed the module namespace in the [config file](https://github.com/rawilk/laravel-modules/blob/master/config/config.php#L12), be sure to change 
`"Modules\\"` to your new namespace.
- If you changed the directory where the modules get stored in the [config file](https://github.com/rawilk/laravel-modules/blob/master/config/config.php#L77), be sure to change
`"Modules/"` to the directory they will be stored in.

**Tip: Be sure sure to run `composer dump-autoload` afterwards.**

## Documentation

Coming soon

## Credits

- [Randall Wilk](https://github.com/rawilk)
- [Nicolas Widart](https://github.com/nwidart)

## License

The MIT License (MIT). Please see the [License file](https://github.com/rawilk/laravel-modules/blob/master/LICENSE) for more information.