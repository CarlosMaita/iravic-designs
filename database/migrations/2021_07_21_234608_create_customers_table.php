<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('telephone')->nullable();
            $table->string('dni')->nullable();
            $table->text('dni_picture')->nullable();
            $table->text('receipt_picture')->nullable();
            $table->float('max_credit');
            $table->string('contact_name');
            $table->string('contact_telephone');
            $table->string('contact_dni');
            $table->enum('qualification', ['Bueno', 'Malo', 'Muy Malo']);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('zone_id')
                ->references('id')
                ->on('zones')
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
        Schema::dropIfExists('customers');
    }
}
