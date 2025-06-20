<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePricesOnProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('products', function (Blueprint $table) {
             $table->dropColumn('price_card_credit');
             $table->dropColumn('price_credit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('products', function (Blueprint $table) {
            $table->float('price_card_credit', 12, 2)->nullable(); //precio por tarjeta de credito
            $table->float('price_credit', 12, 2)->nullable(); //precio por credito
        });
    }
}
