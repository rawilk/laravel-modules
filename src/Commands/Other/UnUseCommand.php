<?php

namespace Rawilk\LaravelModules\Commands\Other;

use Illuminate\Console\Command;

class UnUseCommand extends Command
{
    /** @var string */
    protected $signature = 'module:unuse';

    /** @var string */
    protected $description = 'Forget the used module in the cli session from module:use.';

    public function handle(): void
    {
        $this->laravel['modules']->forgetUsed();

        $this->info('Previous module used is forgotten.');
    }
}
