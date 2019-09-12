<?php return '<?php

namespace Modules\\Blog\\database\\seeds;

use Illuminate\\Database\\Seeder;
use Illuminate\\Database\\Eloquent\\Model;

class BlogDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();

        // $this->call(\'OtherTableSeeder\');
    }
}
';
