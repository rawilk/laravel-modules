---
title: Creating a Module
permalink: /docs/basic-usage/creating-a-module/
doc_section_id: basic-usage
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

By default when you create a new module, the command will add some resources like a seeder, config file,
service provider, etc. automatically. If you don't want these generated for you, you can add the `--plain`
flag to generate a plain module.

```bash
php artisan module:make Blog --plain
# or
php artisan module:make Blog -p
```

## Naming Convention

Because the modules are being autoloaded using **psr-4**, it is strongly recommended to use StudlyCase naming conventions.

## Folder Structure

Below is a sample folder structure of the laravel app using the default `laravel-modules` configuration, as well
as the folder structure for a generated module.

```
app/
bootstrap/
config/
database/
Modules/
    |--- Blog/
         |--- config/
              |--- config.php
         |--- database/
              |--- migrations/
              |--- seeds/
                   |--- BlogDatabaseSeeder.php
         |--- Http/
              |--- Controllers/
              |--- Requests/
         |--- Models/
         |--- Providers/
              |--- BlogServiceProvider.php
         |--- resources/
              |--- assets/
              |--- lang/
              |--- views/
         |--- routes/
              |--- web.php
         |--- tests/
         |--- composer.json
         |--- module.json
         |--- package.json
         |--- start.php
         |--- webpack.mix.js
public/
resources/
routes/
storage/
tests/
vendor/
.env
artisan
composer.json
composer.lock
package.json
phpunit.xml
server.php
webpack.mix.js
```