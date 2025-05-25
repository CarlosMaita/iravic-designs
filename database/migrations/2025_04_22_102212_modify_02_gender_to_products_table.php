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
        DB::statement("ALTER TABLE products MODIFY COLUMN gender ENUM('NIÑO', 'NIÑA', 'UNISEX')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE products MODIFY COLUMN gender ENUM('F', 'M', 'Unisex Adultos', 'Niño', 'Niña', 'Unisex Niños','Bebe Niño' , 'Bebe Niña', 'Unisex Bebes')");
    }
}
