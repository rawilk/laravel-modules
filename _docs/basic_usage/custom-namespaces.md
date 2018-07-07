---
title: Custom Namespaces
permalink: /docs/basic-usage/custom-namespaces/
doc_section_id: basic-usage
excerpt: Learn more about how laravel-modules creates namespaces for your module's resources.
---

When a new module is generated it also registers new custom namespaces for `Lang`, `View`, and `Config`.
For example, if you create a new module named **Blog**, it will also register a new namespace _blog_ for the module.
You can use that namespace for calling any `Lang`, `View`, or `Config` in the module.

Here are some examples of its usage:

### Lang Namespace

```php?start_inline=true?start_inline=true
Lang::get('blog::group.name');

@trans('blog::group.name');
```

### View Namespace

```php?start_inline=true?start_inline=true
view('blog::index');

view('blog::partials.sidebar');
```

### Config Namespace

```php?start_inline=true?start_inline=true
config('blog.name');
```