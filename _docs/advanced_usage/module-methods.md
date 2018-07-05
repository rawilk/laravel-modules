---
title: Module Methods
permalink: /docs/advanced-usage/module-methods/
doc_section_id: advanced-usage
---

Similar to the `Module` facade, there are many methods available to use on a module instance.
To find a module, you need to use the facade to find it first:

```php
$module = Module::find('blog');
```

Get the module's name:

```php
$module->getName();
```

Get the module's name in lowercase:

```php
$module->getLowerName();
```

Get the module's name in studly case:

```php
$module->getStudlyName();
```

Get the module's path:

```php
$module->getPath();
```

Get an extra path, such as an asset path:

```php
$module->getExtraPath('assets');
```

Disable the module:

```php
$module->disable();
```

Enable the module:

```php
$module->enable();
```

Delete the module:

```php
$module->delete();
```

Get an array of the module's requirements:

```php
$module->getRequires();
```