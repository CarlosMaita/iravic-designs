<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveZonesModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Skip for SQLite (used in testing) as it doesn't support dropping foreign keys
        if (DB::getDriverName() !== 'sqlite') {
            // First, remove the foreign key constraint from customers table
            Schema::table('customers', function (Blueprint $table) {
                $table->dropForeign(['zone_id']);
                $table->dropColumn('zone_id');
            });
        }

        // Then drop the zones table
        Schema::dropIfExists('zones');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Recreate zones table
        Schema::create('zones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('position')->default(0);
            $table->text('address_destination')->nullable();
            $table->string('latitude_destination')->nullable();
            $table->string('longitude_destination')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Skip for SQLite (used in testing) as it doesn't support foreign keys in this context
        if (DB::getDriverName() !== 'sqlite') {
            // Re-add zone_id column to customers table
            Schema::table('customers', function (Blueprint $table) {
                $table->unsignedBigInteger('zone_id')->nullable();
                $table->foreign('zone_id')
                    ->references('id')
                    ->on('zones')
                    ->onDelete('cascade');
            });
        }
    }
}
