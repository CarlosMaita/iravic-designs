<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentEnhancementsToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->after('id');
            $table->string('status')->default('pendiente')->after('amount');
            $table->string('payment_method')->default('pago_movil')->after('status');
            $table->string('reference_number')->nullable()->after('payment_method');
            $table->datetime('mobile_payment_date')->nullable()->after('reference_number');

            // Add foreign key for orders
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
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
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn([
                'order_id',
                'status',
                'payment_method',
                'reference_number',
                'mobile_payment_date'
            ]);
        });
    }
}
