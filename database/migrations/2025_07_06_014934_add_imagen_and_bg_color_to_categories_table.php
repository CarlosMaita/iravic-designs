<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImagenAndBgColorToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('image_banner')->nullable()->after('name');
            $table->string('bg_banner¡')->default('#ffffff')->after('image_banner');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // SQLite doesn't support multiple dropColumn in a single call
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('image_banner');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('bg_banner');
        });
    }
}
