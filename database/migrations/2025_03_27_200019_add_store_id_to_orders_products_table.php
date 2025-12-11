<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddStoreIdToOrdersProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders_products', function (Blueprint $table) {
            $table->unsignedBigInteger('store_id')->nullable()->after('product_id');
            if (DB::getDriverName() !== 'sqlite') {
                $table->foreign('store_id')->references('id')->on('stores')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders_products', function (Blueprint $table) {
            // Skip for SQLite (used in testing) as it doesn't support dropping foreign keys
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['store_id']);
            }
            $table->dropColumn('store_id');
        });
    }
}
