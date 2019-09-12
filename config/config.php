<?php

use Illuminate\Routing\Controller;
use Illuminate\Mail\Mailable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Rawilk\LaravelModules\Activators\DatabaseActivator;
use Rawilk\LaravelModules\Activators\FileActivator;
use Rawilk\LaravelModules\Models\Module;

return [
    /*
    |--------------------------------------------------------------------------
    | Module Namespace
    |--------------------------------------------------------------------------
    |
    | Default module namespace
    |
    */
    'namespace' => 'Modules',

    /*
    |--------------------------------------------------------------------------
    | Module Stubs
    |--------------------------------------------------------------------------
    |
    | Default module stubs
    |
    */
    'stubs' => [
        'enabled' => false,
        'path'    => base_path() . '/vendor/rawilk/laravel-modules/src/Commands/stubs',
        'files'   => [
            'assets/js/app'         => 'resources/js/app.js',
            'assets/sass/app'       => 'resources/sass/app.scss',
            'composer'              => 'composer.json',
            'package'               => 'package.json',
            'routes/web'            => 'routes/web.php',
            'scaffold/assets'       => 'assets.json',
            'scaffold/config'       => 'config/config.php',
            'scaffold/module-views' => 'config/module-views.php',
            'views/index'           => 'resources/views/index.blade.php',
            'webpack'               => 'webpack.mix.js',
        ],
        'replacements' => [
            'composer' => [
                'LOWER_NAME',
                'STUDLY_NAME',
                'VENDOR',
                'AUTHOR_NAME',
                'AUTHOR_EMAIL',
                'MODULE_NAMESPACE'
            ],
            'json'                  => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE'],
            'routes/web'            => ['LOWER_NAME', 'STUDLY_NAME'],
            'scaffold/config'       => ['STUDLY_NAME'],
            'scaffold/module-views' => ['LOWER_NAME'],
            'views/index'           => ['LOWER_NAME'],
            'webpack'               => ['LOWER_NAME'],
        ],
        'gitkeep' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Base Classes
    |--------------------------------------------------------------------------
    |
    | These are the default base classes that certain classes, such as a controller,
    | will extend by default when generated.
    |
    */
    'base_classes' => [
        'controller' => Controller::class,
        'mail'       => Mailable::class,
        'model'      => Model::class,
        'request'    => FormRequest::class,
        'repository' => 'App\Repositories\BaseRepository',
    ],

    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Modules Path
        |--------------------------------------------------------------------------
        |
        | This path is used for saving the generated module. This path will also
        | be added automatically to the list of scanned folders.
        |
        */
        'modules' => base_path('Modules'),

        /*
        |--------------------------------------------------------------------------
        | Modules Assets Path
        |--------------------------------------------------------------------------
        |
        | The base path to where module assets get stored.
        |
        */
        'assets' => public_path('modules'),

        /*
        |--------------------------------------------------------------------------
        | Migration Path
        |--------------------------------------------------------------------------
        |
        | This is where migration files are published when the
        | 'module:publish-migration' command is run.
        |
        */
        'migration' => base_path('database/migrations'),

        /*
        |--------------------------------------------------------------------------
        | Generator Paths
        |--------------------------------------------------------------------------
        |
        | These are paths where to where folders will be generated on module creation.
        | Set the generate key to false to not generate that folder.
        |
        */
        'generator' => [
            'assets'        => ['path' => 'resources/assets', 'generate' => true],
            'command'       => ['path' => 'Console', 'generate' => false],
            'config'        => ['path' => 'config', 'generate' => true],
            'controller'    => ['path' => 'Http/Controllers', 'generate' => true],
            'emails'        => ['path' => 'Mail', 'generate' => false],
            'event'         => ['path' => 'Events', 'generate' => false],
            'factory'       => ['path' => 'database/factories', 'generate' => false],
            'jobs'          => ['path' => 'Jobs', 'generate' => false],
            'lang'          => ['path' => 'resources/lang', 'generate' => true],
            'listener'      => ['path' => 'Listeners', 'generate' => false],
            'middleware'    => ['path' => 'Http/Middleware', 'generate' => false],
            'migration'     => ['path' => 'database/migrations', 'generate' => true],
            'model'         => ['path' => 'Models', 'generate' => true],
            'notifications' => ['path' => 'Notifications', 'generate' => false],
            'policies'      => ['path' => 'Policies', 'generate' => false],
            'provider'      => ['path' => 'Providers', 'generate' => true],
            'request'       => ['path' => 'Http/Requests', 'generate' => true],
            'repository'    => ['path' => 'Repositories', 'generate' => false],
            'resource'      => ['path' => 'Transformers', 'generate' => false],
            'rules'         => ['path' => 'Rules', 'generate' => false],
            'seeder'        => ['path' => 'database/seeds', 'generate' => true],
            'test'          => ['path' => 'tests', 'generate' => true],
            'views'         => ['path' => 'resources/views', 'generate' => true],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Scan Path
    |--------------------------------------------------------------------------
    |
    | Define which folders to scan. By default will scan the vendor directory.
    | This is useful if you host the package on the packagist website.
    |
    */
    'scan' => [
        'enabled' => false,
        'paths'   => [
            base_path('vendor/*/*')
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Composer File Template
    |--------------------------------------------------------------------------
    |
    | Here is the config for the composer.json file generated by this package.
    |
    */
    'composer' => [
        'vendor' => 'vendor',
        'author' => [
            'name'  => '',
            'email' => '',
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Enable or disable caching of modules
    |
    */
    'cache' => [
        'enabled'  => false,
        'key'      => 'laravel-modules',
        'lifetime' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Registrations
    |--------------------------------------------------------------------------
    |
    | Choose what laravel-modules will register as custom namespaces.
    | Setting one to false will require you to register that part in
    | your own service provider class.
    |
    */
    'register' => [
        'translations' => true,
        'files'        => 'register' // load files on 'boot' or 'register' in service provider
    ],

    /*
    |--------------------------------------------------------------------------
    | Activators
    |--------------------------------------------------------------------------
    |
    | You can define new types of activators here, i.e. file, database, etc. The
    | only required parameter is 'class'.
    | The file activator will store the activation status in
    | 'storage/installed_modules'.
    |
    */
    'activators' => [
        'database' => [
            'class'          => DatabaseActivator::class,
            'cache-key'      => 'activator.db.installed.%s',
            'cache-lifetime' => 604800,
        ],
        'file' => [
            'class'          => FileActivator::class,
            'statuses-file'  => storage_path('module_statuses.json'),
            'cache-key'      => 'activator.installed',
            'cache-lifetime' => 604800,
        ],
    ],

    'activator' => 'file',

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | Here you may override the models used in this package with your own models.
    | Just be sure to implement \Rawilk\LaravelModules\Contracts\ModuleModel
    | in your model class.
    |
    */
    'models' => [
        'module' => Module::class,
    ],
];
