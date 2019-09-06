<?php return '<?php

namespace Modules\\Blog\\Jobs\\Nested\\Path;

use Illuminate\\Bus\\Queueable;
use Illuminate\\Queue\\SerializesModels;
use Illuminate\\Queue\\InteractsWithQueue;
use Illuminate\\Contracts\\Queue\\ShouldQueue;
use Illuminate\\Foundation\\Bus\\Dispatchable;

class SomeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {

    }
}
';
