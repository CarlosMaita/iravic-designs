<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->double('quota');
            $table->integer('amount_quotas')->default(1);
            $table->date('start_date');
            $table->double('total')->default(0);
            $table->string('status')->nullable();
            // link soft to order 
            $table->unsignedBigInteger('order_id')->nullable();
            // link to customer
            $table->foreignId('customer_id')
                ->references('id')
                ->on('customers');

           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credits');
    }
}
