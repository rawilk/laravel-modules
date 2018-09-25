<?php return '<?php

namespace Modules\\Blog\\Providers;

use Illuminate\\Support\\ServiceProvider;

class MyBlogServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
     protected $defer = false;

     /**
      * Register any application services.
      *
      * @return void
      */
     public function register()
     {
     }

     /**
      * Bootstrap any application services.
      *
      * @return void
      */
     public function boot()
     {
     }
}
';
