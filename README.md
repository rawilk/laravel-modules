# Laravel-Modules

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rawilk/laravel-modules.svg?style=for-the-badge)](https://packagist.org/packages/rawilk/laravel-modules)
[![Build Status](https://img.shields.io/travis/rawilk/laravel-modules/master.svg?style=for-the-badge)](https://travis-ci.org/rawilk/laravel-modules)
[![GitHub issues](https://img.shields.io/github/issues/rawilk/laravel-modules.svg?style=for-the-badge)](https://github.com/rawilk/laravel-modules/issues)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=for-the-badge)](LICENSE)
[![Quality Score](https://img.shields.io/scrutinizer/g/rawilk/laravel-modules.svg?style=for-the-badge)](https://scrutinizer-ci.com/g/rawilk/laravel-modules)
[![Total Downloads](https://img.shields.io/packagist/dt/rawilk/laravel-modules.svg?style=for-the-badge)](https://packagist.org/packages/rawilk/laravel-modules)

`rawilk/laravel-modules` is a Laravel package to help build modular apps using modules.
A module is like a Laravel package; it has some views, controllers and models.
Laravel-Modules is supported and tested in Laravel 6.

This package is inspired by [nWidart/laravel-modules](https://github.com/nWidart/laravel-modules).
Although laravel-modules is mainly intended for my own use and has certain parts written to cater
to my needs, you are free to use it.

## Installation

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

- If you changed the module namespace in the [config file](https://github.com/rawilk/laravel-modules/blob/master/config/config.php#L20), be sure to change 
`"Modules\\"` to your new namespace.
- If you changed the directory where the modules get stored in the [config file](https://github.com/rawilk/laravel-modules/blob/master/config/config.php#L91), be sure to change
`"Modules/"` to the directory they will be stored in.

**Tip: Be sure sure to run `composer dump-autoload` afterwards.**

## Database

Database installation and activation of modules is still a work in progress and is not ready for production yet. I
do have plans to finish this functionality in a future release.

## Documentation

Further information and instructions can be found at: https://rawilk.github.io/laravel-modules/

## Credits

- [Randall Wilk](https://randallwilk.com)

## License

The MIT License (MIT). Please see the [License file](https://github.com/rawilk/laravel-modules/blob/master/LICENSE) for more information.
