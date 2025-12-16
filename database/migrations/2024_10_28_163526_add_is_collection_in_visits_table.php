<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsCollectionInVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->boolean( 'is_collection')->default(false);
            $table->boolean( 'is_paid')->default(false);
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
        Schema::table('visits', function (Blueprint $table) {
            $table->dropColumn('is_collection');
        });
        Schema::table('visits', function (Blueprint $table) {
            $table->dropColumn('is_paid');
        });
    }
}
