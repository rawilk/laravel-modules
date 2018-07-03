---
title: Creating a Module
permalink: /docs/creating-a-module/
---

Creating a module is simple and straightforward. Run the following command to create a module.

```bash
php artisan module:make <module-name>
```

Replace `<module-name>` with your module's name.

## Creating Multiple Modules

Laravel-Modules also makes it possible to create multiple modules with one command.

```bash
php artisan module:make Blog User Auth
```