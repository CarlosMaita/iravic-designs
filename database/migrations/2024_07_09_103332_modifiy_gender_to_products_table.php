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
        DB::statement("ALTER TABLE products MODIFY COLUMN gender ENUM('F', 'M', 'Unisex Adultos', 'Niño', 'Niña', 'Unisex Niños','Bebe Niño' , 'Bebe Niña', 'Unisex Bebes')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE products MODIFY COLUMN gender ENUM('F','M', 'Niño', 'Niña','Unisex Niños','Unisex Adultos')"); 
    }
}
