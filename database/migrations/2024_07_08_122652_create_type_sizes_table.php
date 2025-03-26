<?php

use App\Constants\GenderConstants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_sizes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('base_category_id')->references('id')->on('base_categories')->constrained();
            $table->string('genders')->nullable();
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
        Schema::dropIfExists('type_sizes');
    }
}
