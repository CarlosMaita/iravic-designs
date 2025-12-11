<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ModifyStocksToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Skip for SQLite (used in testing) as it doesn't support MODIFY syntax
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement('ALTER TABLE products MODIFY stock_depot INTEGER DEFAULT 0');
        DB::statement('ALTER TABLE products MODIFY stock_local INTEGER DEFAULT 0');
        DB::statement('ALTER TABLE products MODIFY stock_truck INTEGER DEFAULT 0');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Skip for SQLite (used in testing) as it doesn't support MODIFY syntax
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        
        DB::statement('ALTER TABLE products MODIFY stock_depot VARCHAR(255) DEFAULT "0"');
        DB::statement('ALTER TABLE products MODIFY stock_local VARCHAR(255) DEFAULT "0"');
        DB::statement('ALTER TABLE products MODIFY stock_truck VARCHAR(255) DEFAULT "0"');
    }
}
