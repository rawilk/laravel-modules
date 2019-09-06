<?php return '<?php

namespace Modules\\Blog\\Listeners;

use Modules\\Blog\\Listeners\\Events\\UserWasCreated;

class NotifyUsersOfANewPost
{
    public function handle(UserWasCreated $event): void
    {

    }
}
';
