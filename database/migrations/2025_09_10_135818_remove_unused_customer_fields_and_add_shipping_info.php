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
        Schema::table('customers', function (Blueprint $table) {
            // Remove unused fields according to requirements
            
            // Remove C.I. picture
            $table->dropColumn('dni_picture');
            
            // Remove telephone field 
            $table->dropColumn('telephone');
            
            // Remove financial information fields
            $table->dropColumn('max_credit');
            $table->dropColumn('receipt_picture');
            $table->dropColumn('card_front');
            $table->dropColumn('card_back');
            $table->dropColumn('collection_day');
            $table->dropColumn('collection_frequency');
            $table->dropColumn('days_to_notify_debt');
            
            // Remove contact person information
            $table->dropColumn('contact_name');
            $table->dropColumn('contact_telephone');
            $table->dropColumn('contact_dni');
            
            // Remove address and location information
            $table->dropColumn('address');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->dropColumn('address_picture');
        });
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
