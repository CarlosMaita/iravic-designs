<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            // Note: box_id column removed as boxes module is deprecated
            // $table->unsignedBigInteger('box_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->datetime('date');
            $table->string('amount')->default(0);
            $table->text('comment')->nullable();
            $table->tinyInteger('payed_bankwire')->default(0)->nullable();
            $table->tinyInteger('payed_card')->default(0)->nullable();
            $table->tinyInteger('payed_cash')->default(0)->nullable();
            $table->timestamps();

            // Foreign key to boxes table removed as boxes module is deprecated
            // $table->foreign('box_id')
            //     ->references('id')
            //     ->on('boxes')
            //     ->onDelete('cascade');
                
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');
            
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('payments');
    }
}
