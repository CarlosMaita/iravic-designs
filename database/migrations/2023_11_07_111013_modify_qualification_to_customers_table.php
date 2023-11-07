<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ModifyQualificationToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE customers MODIFY COLUMN qualification ENUM('Nuevo','Muy Bueno', 'Bueno', 'Malo', 'Muy Malo')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE customers MODIFY COLUMN qualification ENUM('Muy Bueno', 'Bueno', 'Malo', 'Muy Malo')");
    }
}
