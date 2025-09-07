<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderStatusAndShippingFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status')->default('creada')->after('total');
            $table->string('shipping_name')->nullable()->after('status');
            $table->string('shipping_dni')->nullable()->after('shipping_name');
            $table->string('shipping_phone')->nullable()->after('shipping_dni');
            $table->string('shipping_agency')->nullable()->after('shipping_phone');
            $table->text('shipping_address')->nullable()->after('shipping_agency');
            $table->string('shipping_tracking_number')->nullable()->after('shipping_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'shipping_name',
                'shipping_dni',
                'shipping_phone',
                'shipping_agency',
                'shipping_address',
                'shipping_tracking_number'
            ]);
        });
    }
}
