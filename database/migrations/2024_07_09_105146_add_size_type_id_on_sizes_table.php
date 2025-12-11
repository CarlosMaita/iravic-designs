<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddSizeTypeIdOnSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sizes', function (Blueprint $table) {
            if (DB::getDriverName() === 'sqlite') {
                $table->unsignedBigInteger('type_size_id')->nullable();
            } else {
                $table->foreignId('type_size_id')->nullable()->constrained('type_sizes')
                    ->onUpdate('cascade');
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
        Schema::table('sizes', function (Blueprint $table) {
            // Skip for SQLite (used in testing) as it doesn't support dropping foreign keys
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['type_size_id']);
            }
            $table->dropColumn('type_size_id');
        });
    }
}
