<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCombinationsIndexToProductsImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products_images', function (Blueprint $table) {
            $table->bigInteger('combination_index')->nullable()->unsigned()->after('color_id');
            $table->string('temp_code')->nullable()->after('combination_index');
            $table->string('url_original')->nullable()->after('temp_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products_images', function (Blueprint $table) {
            $table->dropColumn('combination_index');
            $table->dropColumn('temp_code');
            $table->dropColumn('url_original');
        });
    }
}
