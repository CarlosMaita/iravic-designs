<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveSchedulingModule extends Migration
{
    /**
     * Run the migrations.
     * Remove all scheduling module related tables and fields
     *
     * @return void
     */
    public function up()
    {
        // Drop visits table first (has foreign key to schedules)
        Schema::dropIfExists('visits');
        
        // Drop schedules table
        Schema::dropIfExists('schedules');
        
        // Remove is_pending_to_schedule field from customers table
        if (Schema::hasColumn('customers', 'is_pending_to_schedule')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('is_pending_to_schedule');
            });
        }
    }

    /**
     * Reverse the migrations.
     * Note: This creates basic table structure without all columns for rollback
     *
     * @return void
     */
    public function down()
    {
        // Recreate schedules table
        Schema::create('schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('completed')->default(0);
            $table->date('date');
            $table->timestamps();
        });

        // Recreate visits table with basic structure
        Schema::create('visits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('schedule_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('user_creator_id')->nullable();
            $table->unsignedBigInteger('user_responsable_id')->nullable();
            $table->text('comment')->nullable();
            $table->date('date');
            $table->tinyInteger('is_completed')->default(0);
            $table->datetime('completed_date')->nullable();
            $table->timestamps();

            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('user_creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_responsable_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Add back is_pending_to_schedule field to customers table
        if (!Schema::hasColumn('customers', 'is_pending_to_schedule')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->tinyInteger('is_pending_to_schedule')->default(0)->after('qualification');
            });
        }
    }
}
