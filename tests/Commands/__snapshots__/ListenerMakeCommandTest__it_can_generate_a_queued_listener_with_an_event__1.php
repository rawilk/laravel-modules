<?php return '<?php

namespace Modules\\Blog\\Listeners;

use Illuminate\\Queue\\InteractsWithQueue;
use Illuminate\\Contracts\\Queue\\ShouldQueue;
use Modules\\Blog\\Listeners\\Events\\UserWasCreated;

class NotifyUsersOfANewPost implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(UserWasCreated $event): void
    {

    }
}
';
