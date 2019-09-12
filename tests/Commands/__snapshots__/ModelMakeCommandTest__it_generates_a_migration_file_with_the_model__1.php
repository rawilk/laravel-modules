<?php return '<?php

use Illuminate\\Support\\Facades\\Schema;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Database\\Migrations\\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(\'posts\', function (Blueprint $table) {
            $table->bigIncrements(\'id\');

            $table->dateTime(\'created_at\')->nullable();
            $table->dateTime(\'updated_at\')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(\'posts\');
    }
}
';
