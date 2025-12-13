<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveOrderIdToVisitsTable extends Migration
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
        
        Schema::table('visits', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Skip for SQLite (used in testing) as it doesn't support dropping foreign keys
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        
        Schema::table('visits', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->after('user_responsable_id');

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('set null');
        });
    }
}
