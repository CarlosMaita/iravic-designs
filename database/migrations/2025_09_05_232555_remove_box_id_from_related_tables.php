<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveBoxIdFromRelatedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Skip entirely - box_id columns are no longer created in initial migrations
        // This migration is kept for historical reference only
        return;
        
        // Original code below (now disabled):
        // Skip for SQLite (used in testing) as it doesn't support dropping foreign keys
        // if (DB::getDriverName() === 'sqlite') {
        //     return;
        // }
        
        // Remove box_id foreign key constraints and columns from orders table
        // Schema::table('orders', function (Blueprint $table) {
        //     $table->dropForeign(['box_id']);
        //     $table->dropColumn('box_id');
        // });

        // Remove box_id foreign key constraints and columns from refunds table
        // Schema::table('refunds', function (Blueprint $table) {
        //     $table->dropForeign(['box_id']);
        //     $table->dropColumn('box_id');
        // });

        // Remove box_id foreign key constraints and columns from payments table
        // Schema::table('payments', function (Blueprint $table) {
        //     $table->dropForeign(['box_id']);
        //     $table->dropColumn('box_id');
        // });

        // Remove box_id foreign key constraints and columns from spendings table
        // Schema::table('spendings', function (Blueprint $table) {
        //     $table->dropForeign(['box_id']);
        //     $table->dropColumn('box_id');
        // });

        // Remove box_id foreign key constraints and columns from debts table
        // Schema::table('debts', function (Blueprint $table) {
        //     $table->dropForeign(['box_id']);
        //     $table->dropColumn('box_id');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Skip for SQLite (used in testing)
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        
        // Add back box_id columns and foreign key constraints
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('box_id')->nullable();
            $table->foreign('box_id')->references('id')->on('boxes')->onDelete('cascade');
        });

        Schema::table('refunds', function (Blueprint $table) {
            $table->unsignedBigInteger('box_id')->nullable();
            $table->foreign('box_id')->references('id')->on('boxes')->onDelete('cascade');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('box_id')->nullable();
            $table->foreign('box_id')->references('id')->on('boxes')->onDelete('cascade');
        });

        Schema::table('spendings', function (Blueprint $table) {
            $table->unsignedBigInteger('box_id')->nullable();
            $table->foreign('box_id')->references('id')->on('boxes')->onDelete('cascade');
        });

        Schema::table('debts', function (Blueprint $table) {
            $table->unsignedBigInteger('box_id')->nullable();
            $table->foreign('box_id')->references('id')->on('boxes')->onDelete('cascade');
        });
    }
}
