<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // SQLite doesn't support multiple dropColumn in a single call
        // Split into separate calls
        $columnsToRemove = [
            'dni_picture',
            'telephone',
            'max_credit',
            'receipt_picture',
            'card_front',
            'card_back',
            'collection_day',
            'collection_frequency',
            'days_to_notify_debt',
            'contact_name',
            'contact_telephone',
            'contact_dni',
            'address',
            'latitude',
            'longitude',
            'address_picture'
        ];
        
        foreach ($columnsToRemove as $column) {
            if (Schema::hasColumn('customers', $column)) {
                Schema::table('customers', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            // Recreate the removed fields
            $table->string('dni_picture')->nullable();
            $table->string('telephone')->nullable();
            $table->string('max_credit')->nullable();
            $table->text('receipt_picture')->nullable();
            $table->text('card_front')->nullable();
            $table->text('card_back')->nullable();
            $table->string('collection_day')->nullable();
            $table->string('collection_frequency')->nullable();
            $table->integer('days_to_notify_debt')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_telephone')->nullable();
            $table->string('contact_dni')->nullable();
            $table->text('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('address_picture')->nullable();
        });
    }
};
