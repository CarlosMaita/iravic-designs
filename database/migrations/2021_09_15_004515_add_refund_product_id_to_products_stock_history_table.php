<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefundProductIdToProductsStockHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products_stock_history', function (Blueprint $table) {
            $table->unsignedBigInteger('refund_product_id')->nullable()->after('product_stock_transfer_id');

            $table->foreign('refund_product_id')
                ->references('id')
                ->on('refunds_products')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products_stock_history', function (Blueprint $table) {
            //
        });
    }
}
