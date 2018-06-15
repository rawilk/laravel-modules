<?php return '<?php

namespace Modules\\Blog\\Console;

use Illuminate\\Console\\Command;

class MyAwesomeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
     protected $signature = \'my:awesome\';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = \'Command description\';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

    }
}
';
