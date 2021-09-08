<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('color_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('size_id')->nullable();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('cover')->nullable();
            $table->tinyInteger('is_regular')->default(0);
            $table->enum('gender', ['M', 'F', 'Niño', 'Niña'])->nullable();
            $table->tinyInteger('is_price_generic')->default(0);
            // $table->string('price')->nullable();
            $table->float('price', 12, 2)->nullable();
            $table->string('stock_depot')->default(0);
            $table->string('stock_local')->default(0);
            $table->string('stock_truck')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('brand_id')
                ->references('id')
                ->on('brands')
                ->onDelete('cascade');
            
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');
            
            $table->foreign('color_id')
                ->references('id')
                ->on('colors')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->foreign('size_id')
                ->references('id')
                ->on('sizes')
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
        Schema::dropIfExists('products');
    }
}
