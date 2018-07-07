---
title: Module Methods
permalink: /docs/advanced-usage/module-methods/
doc_section_id: advanced-usage
excerpt: Learn about the methods available to a single module instance.
---

Similar to the `Module` facade, there are many methods available to use on a module instance.
To find a module, you need to use the facade to find it first:

```php?start_inline=true
$module = Module::find('blog');
```

Get the module's name:

```php?start_inline=true
$module->getName();
```

Get the module's name in lowercase:

```php?start_inline=true
$module->getLowerName();
```

Get the module's name in studly case:

```php?start_inline=true
$module->getStudlyName();
```

Get the module's path:

```php?start_inline=true
$module->getPath();
```

Get an extra path, such as an asset path:

```php?start_inline=true
$module->getExtraPath('assets');
```

Disable the module:

```php?start_inline=true
$module->disable();
```

Enable the module:

```php?start_inline=true
$module->enable();
```

Delete the module:

```php?start_inline=true
$module->delete();
```

Get an array of the module's requirements:

```php?start_inline=true
$module->getRequires();
```