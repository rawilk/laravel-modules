<?php return '<?php

use Illuminate\\Support\\Facades\\Schema;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Database\\Migrations\\Migration;

class CreateProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(\'product_details\', function (Blueprint $table) {
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
        Schema::dropIfExists(\'product_details\');
    }
}
';
