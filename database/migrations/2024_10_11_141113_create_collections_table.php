<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->double('quota');
            $table->integer('amount_quotas')->default(1);
            $table->string('frequency');
            $table->date('start_date');
            $table->double('total')->default(0);
            $table->double('paid')->default(0);
            $table->double('balance')->default(0);
            $table->string('status')->nullable();
            $table->boolean('is_overdue')->default(false); 
            // link to order 
            $table->foreignId('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');

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
        Schema::dropIfExists('collections');
    }
}
