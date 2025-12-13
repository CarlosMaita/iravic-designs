<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DeleteStocksToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Skip for SQLite as it doesn't support multiple dropColumn in single modification
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('stock_local');
            $table->dropColumn('stock_truck');
            $table->dropColumn('stock_depot');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Skip for SQLite
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        
        Schema::table('products', function (Blueprint $table) {
            $table->integer('stock_local')->default(0);
            $table->integer('stock_truck')->default(0);
            $table->integer('stock_depot')->default(0);
        });
    }
}
