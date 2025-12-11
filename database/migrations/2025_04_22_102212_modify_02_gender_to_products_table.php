<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class Modify02GenderToProductsTable extends Migration
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
        
        DB::statement("ALTER TABLE products MODIFY COLUMN gender ENUM('NIÑO', 'NIÑA', 'UNISEX')");
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
        
        DB::statement("ALTER TABLE products MODIFY COLUMN gender ENUM('F', 'M', 'Unisex Adultos', 'Niño', 'Niña', 'Unisex Niños','Bebe Niño' , 'Bebe Niña', 'Unisex Bebes')");
    }
}
