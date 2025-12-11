<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModifiyGenderToProductsTable extends Migration
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
        
        DB::statement("ALTER TABLE products MODIFY COLUMN gender ENUM('F', 'M', 'Unisex Adultos', 'Niño', 'Niña', 'Unisex Niños','Bebe Niño' , 'Bebe Niña', 'Unisex Bebes')");
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
        
        DB::statement("ALTER TABLE products MODIFY COLUMN gender ENUM('F','M', 'Niño', 'Niña','Unisex Niños','Unisex Adultos')"); 
    }
}
