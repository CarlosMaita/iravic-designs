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
            $table->id();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('color_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('size_id')->nullable();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('cover')->nullable();
            $table->tinyInteger('is_regular')->default(0);
            // $table->enum('color', ['Amarillo', 'Azul', 'Rojo', 'Verde'])->nullable();
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->tinyInteger('is_child_size')->default(0);

            // $table->enum('size', ['32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46'])->nullable();
            // $table->enum('size_shoes', ['32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46'])->nullable();
            // $table->enum('size_clothes', ['XS', 'S', 'M', 'L', 'XL', 'XXL'])->nullable();
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
