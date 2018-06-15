<?php

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
			'start'           => 'start.php',
			'routes'          => 'routes/web.php',
			'scaffold/config' => 'config/config.php',
			'composer'        => 'composer.json',
			'webpack'         => 'webpack.mix.js',
			'package'         => 'package.json'
		],
		'replacements' => [
			'start'           => ['LOWER_NAME', 'ROUTES_LOCATION'],
			'routes'          => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE'],
			'webpack'         => ['LOWER_NAME'],
			'json'            => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE'],
			'scaffold/config' => ['STUDLY_NAME'],
			'composer'        => [
				'LOWER_NAME',
				'STUDLY_NAME',
				'VENDOR',
				'AUTHOR_NAME',
				'AUTHOR_EMAIL',
				'MODULE_NAMESPACE'
			]
		],
		'gitkeep' => false,
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
		'controller' => 'Illuminate\Routing\Controller',
		'mail'       => 'Illuminate\Mail\Mailable',
		'model'      => 'Illuminate\Database\Eloquent\Model',
		'request'    => 'Illuminate\Foundation\Http\FormRequest',
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
			'config'        => ['path' => 'config', 'generate' => true],
			'command'       => ['path' => 'Console', 'generate' => false],
			'migration'     => ['path' => 'database/migrations', 'generate' => true],
			'seeder'        => ['path' => 'database/seeds', 'generate' => true],
			'factory'       => ['path' => 'database/factories', 'generate' => false],
			'model'         => ['path' => 'Models', 'generate' => true],
			'controller'    => ['path' => 'Http/Controllers', 'generate' => true],
			'filter'        => ['path' => 'Http/Middleware', 'generate' => false],
			'request'       => ['path' => 'Http/Requests', 'generate' => true],
			'provider'      => ['path' => 'Providers', 'generate' => true],
			'assets'        => ['path' => 'resources/assets', 'generate' => true],
			'lang'          => ['path' => 'resources/lang', 'generate' => true],
			'views'         => ['path' => 'resources/views', 'generate' => true],
			'test'          => ['path' => 'tests', 'generate' => true],
			'repository'    => ['path' => 'Repositories', 'generate' => false],
			'event'         => ['path' => 'Events', 'generate' => false],
			'listener'      => ['path' => 'Listeners', 'generate' => false],
			'policies'      => ['path' => 'Policies', 'generate' => false],
			'rules'         => ['path' => 'Rules', 'generate' => false],
			'jobs'          => ['path' => 'Jobs', 'generate' => false],
			'emails'        => ['path' => 'Mail', 'generate' => false],
			'notifications' => ['path' => 'Notifications', 'generate' => false],
			'resource'      => ['path' => 'Transformers', 'generate' => false],
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
];
