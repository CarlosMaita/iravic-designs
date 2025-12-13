<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveZoneConstraintFromCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Skip for SQLite (used in testing) as it doesn't support dropping foreign keys
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        
        // Check if zone_id column exists before trying to drop foreign key and column
        if (Schema::hasColumn('customers', 'zone_id')) {
            Schema::table('customers', function (Blueprint $table) {
                // Drop the foreign key constraint
                $table->dropForeign(['zone_id']);
                // Drop the zone_id column
                $table->dropColumn('zone_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
}
