<?php return '<?php

namespace Modules\\Blog\\Listeners;

use Modules\\Blog\\Events\\UserWasCreated;
use Illuminate\\Queue\\InteractsWithQueue;
use Illuminate\\Contracts\\Queue\\ShouldQueue;

class NotifyUsersOfANewPost
{
    /**
     * Handle the event.
     *
     * @param Modules\\Blog\\Events\\UserWasCreated $event
     * @return void
     */
    public function handle(UserWasCreated $event)
    {

    }
}
';
