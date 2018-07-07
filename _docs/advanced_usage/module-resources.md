---
title: Module Resources
permalink: /docs/advanced-usage/module-resources/
doc_section_id: advanced-usage
excerpt: Learn how to register and publish various resources from your modules.
---

Your modules will most likely contain various resources such as configuration, views, translation files, etc.
In order for your module to correctly load and if you want them to be able to be published you need to let
Laravel know about them as in any regular package.

<div class="alert alert-success">
    <div class="alert-content">
        <h4 class="alert-title">Note</h4>
        <p>
            By default, these resources are loaded in the service provider generated with a module (using <code>module:make</code>),
            unless the <code>plain</code> flag is used, in which cass you will need to handle this logic yourself.
            All of the examples below should be placed in the <code>boot</code> method of the service provider.
        </p>
    </div>
</div>

<div class="alert alert-success">
    <div class="alert-content">
        <h4 class="alert-title">Note</h4>
        <p>
            The following examples are assuming a module name of "Blog". Don't forget to change the paths
            to your module.
        </p>
    </div>
</div>

### Configuration

To load and publish your module's configuration you need to add the following to your module's service provider.
If you don't want to publish the configuration, you can skip the `$this->publishes()` method call.

```php?start_inline=true
$this->publishes([
    __DIR__ . '../../config/config.php' => config_path('blog.php')
], 'config');

$this->mergeConfigFrom(
    __DIR__ . '../../config/config.php', 
    'blog'
);
```

### Views

```php?start_inline=true
$viewPath = base_path('resources/views/modules/blog');

$sourcePath = __DIR__ . '../resources/views');

$this->publishes([
    $sourcePath => $viewPath
]);

$this->loadViewsFrom(array_merge(array_map(function ($path) {
    return $path . '/modules/blog';
}, \Config::get('view.paths')), [$sourcePath]), 'blog');
```

The important part here is the `loadViewsFrom` method call. If you don't want to publish your views
to the Laravel views folder, you can remove the `$this->publishes()` call.

### Language Files

If you want to use language files in the module, you need to add the following to your service provider.

```php?start_inline=true
$langPath = base_path('resources/lang/modules/blog');

if (is_dir($langPath)) {
    $this->loadTranslationsFrom($langPath, 'blog');
} else {
    $this->loadTranslationsFrom(__DIR__ . '../resources/lang', 'blog');
}
```

### Factories

If you want to use Laravel factories you will need the following in your service provider.

```php?start_inline=true
$this->app->singleton(Factory::class, function () {
    return Factory::construct(__DIR__ . '/database/factories');
});
```