---
title: Compiling Assets
permalink: /docs/basic-usage/compiling-assets/
doc_section_id: basic-usage
---

## Installation & Setup

When you create a new module it also creates assets for CSS/JS and the `webpack.mix.js` configuration file.

```bash
php artisan module:make Blog
```

Once you have a module created, change your directory to that module in the terminal.

```bash
cd Modules/Blog
```

The default `package.json` file includes everything you need to get started. You may install the dependencies
it references by running this command:

```bash
npm install
```

## Running Laravel Mix

Laravel mix is a configuration layer on top of [Webpack](https://webpack.js.org). To run your mix tasks you only
need to execute one of the NPM scripts that are included in the default laravel-modules `package.json` file.
The tasks that are included are basically the same as the root npm scripts. Feel free to modify the scripts to 
fit your needs.

```bash
// Run mix for development
npm run dev

# or
npm run watch

// Run mix for production
npm run production
```

**Tip:** Make sure you are in the directory of the module you are compiling assets for.

If you version your files with mix, you can still reference Laravel's global mix function within
your module's views to load the appropriately hashed asset. The mix function will automatically determine
the current name of the hashed file.

```html
// Modules/Blog/resources/views/layouts/master.blade.php

<link rel="stylesheet" href="{!! mix('css/blog.css') !!}">

<script src="{!! mix('js/blog.js') !!}"></script>
```

For more information on Laravel Mix you can view the documentation here: https://laravel.com/docs/mix

> Note: To prevent the main Laravel Mix configuration from overwriting the `public/mix-manifest.json` file,
> ensure you do the following in your modules.

```bash
npm install laravel-mix-merge-manifest --save-dev
```

Once you have `laravel-mix-merge-manifest` installed, you need to add `mix.mergeManifest()` to your module's `webpack.mix.js` file.

```js
const { mix } = require('laravel-mix');

// Add this line
require('laravel-mix-merge-manifest');

// Also add this line
mix.mergeManifest();

// Run through laravel mix as normal
mix
    .js(__dirname + '/resources/assets/js/app.js', 'js/your-file-name.js');
```

By default, these two steps are done for you if you generate a module normally. You only need to do this
if you create a module manually or if you generate a plain module.