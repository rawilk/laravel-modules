---
title: Registering Module Events
permalink: /docs/advanced-usage/registering-module-events/
doc_section_id: advanced-usage
---

A module may contain events and event listeners. You can create these classes manually, or with
the following artisan commands:

```bash
php artisan module:make-event BlogPostWasUpdated Blog

php artisan module:make-listener NotifyAdminOfNewPost Blog
```

Once your events and listeners are created, you need to register them with Laravel. This can be done
in one of two ways:

- In your module service provider, you can add a line like this to register the event and listener:
`$this->app['events']->listen(BlogPostWasUpdated::class, NotifyAdminOfNewPost::class);`
- The other way is to create an event service provider for your module which will contain all of its
events, similar to the `EventServiceProvider` under the app/ namespace.

For better organization, it is recommended to create the dedicated event service provider.

### Creating an EventServiceProvider

If you have multiple events in your module, you might find it easier to have all events and their listeners in a
dedicated service provider. This is where the EventServiceProvider comes in.

Going with the example above, create a new class called `EventServiceProvider` in the `Modules/Blog/Providers`
directory for the Blog module.

The class needs to look like this:

```php
<?php

namespace Modules\Blog\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [];
}
```

You could also generate this file using the `module:make-event` artisan command, just make sure you are extending
the EventServiceProvider class.

<div class="alert alert-success">
    <div class="alert-content">
        <p class="text-bold">
            Don't forget to load this service provider, for instance by adding it to the module.json
            file of your module.
        </p>
    </div>
</div>

This is now like the regular EventServiceProvider in the `app/` namespace. Based off the example above,
the `listen` property of the class will now look like this:

```php
// ...
class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BlogPostWasUpdated::class => [
            NotifyAdminOfNewPost::class
        ]
    ];
}
```