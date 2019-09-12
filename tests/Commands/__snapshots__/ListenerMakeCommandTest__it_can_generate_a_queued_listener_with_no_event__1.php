<?php return '<?php

namespace Modules\\Blog\\Listeners;

use Illuminate\\Queue\\InteractsWithQueue;
use Illuminate\\Contracts\\Queue\\ShouldQueue;

class NotifyUsersOfANewPost implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle($event): void
    {

    }
}
';
