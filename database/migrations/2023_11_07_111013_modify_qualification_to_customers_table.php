<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModifyQualificationToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Skip for SQLite (used in testing) as it doesn't support MODIFY COLUMN or ENUM
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        
        DB::statement("ALTER TABLE customers MODIFY COLUMN qualification ENUM('Nuevo','Muy Bueno', 'Bueno', 'Malo', 'Muy Malo')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Skip for SQLite (used in testing) as it doesn't support MODIFY COLUMN or ENUM
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        
        DB::statement("ALTER TABLE customers MODIFY COLUMN qualification ENUM('Muy Bueno', 'Bueno', 'Malo', 'Muy Malo')");
    }
}
