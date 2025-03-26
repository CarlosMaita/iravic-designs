<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtsOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debts_order_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('debt_id')->nullable();
            $table->unsignedBigInteger('order_product_id')->nullable();
            $table->unsignedBigInteger('refund_product_id')->nullable();
            $table->string('type')->nullable();
            $table->string('product_name');
            $table->string('product_price');
            $table->integer('qty');
            $table->string('total');
            $table->timestamps();

            $table->foreign('debt_id')
                ->references('id')
                ->on('debts')
                ->onDelete('cascade');
                
            $table->foreign('order_product_id')
                ->references('id')
                ->on('orders_products')
                ->onDelete('cascade');

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
        Schema::dropIfExists('debts_order_products');
    }
}
