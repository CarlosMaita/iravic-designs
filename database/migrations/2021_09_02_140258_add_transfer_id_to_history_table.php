<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransferIdToHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products_stock_history', function (Blueprint $table) {
            $table->unsignedBigInteger('product_stock_transfer_id')->nullable()->after('order_product_id');
            
            $table->foreign('product_stock_transfer_id')
                ->references('id')
                ->on('products_stock_transfer')
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
        Schema::table('history', function (Blueprint $table) {
            //
        });
    }
}
