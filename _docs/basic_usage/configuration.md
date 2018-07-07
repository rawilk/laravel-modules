---
title: Configuration
permalink: /docs/basic-usage/configuration/
doc_section_id: basic-usage
excerpt: Learn more about each of the configuration options available for laravel-modules.
---

If you need to override certain `laravel-modules` default configurations, you can publish the package configuration
using the following command:

```bash
php artisan vendor:publish --provider="Rawilk\LaravelModules\LaravelModulesServiceProvider"
```

In the published configuration file you can configure the following items:

### Default Namespace
What the default namespace will be when generating modules.

Key: `namespace`

Default: `Modules`

### Module Stubs
Overwrite the default generated stubs to be used when generating modules. This can be useful to customize the output
of different files.

Key: `stubs`

### Base Classes
Overwrite the base classes used when generating stubs for certain files, such as a controller or an Eloquent model.

Key: `base_classes`

### Default Paths
Overwrite the default paths used throughout the package for generating the files.

Key: `paths`

### Can Additional Folders for Modules
This is disabled by default. Once enabled, the package will look for modules in the specified array of paths.

Key: `scan`

### Composer File Template
This allows you to customize what gets populated in the module's `composer.json` file when the module is generated.

Key: `composer`

### Caching
If you have many modules it's a good idea to cache this information (like the multiple `module.json` files for example).

Key: `cache`

### Register Custom Namespaces
Decide which custom namespaces need to be registered by the package. If one is set to false, the package
won't handle its registration.

Key: `register`