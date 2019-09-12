<?php return '<?php

namespace Modules\\Blog\\Jobs;

use Illuminate\\Bus\\Queueable;
use Illuminate\\Foundation\\Bus\\Dispatchable;

class SomeJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function handle(): void
    {

    }
}
';
