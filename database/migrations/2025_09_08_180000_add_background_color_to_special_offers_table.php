<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('special_offers') && !Schema::hasColumn('special_offers', 'background_color')) {
            Schema::table('special_offers', function (Blueprint $table) {
                $table->string('background_color', 20)->nullable()->after('image');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('special_offers') && Schema::hasColumn('special_offers', 'background_color')) {
            Schema::table('special_offers', function (Blueprint $table) {
                $table->dropColumn('background_color');
            });
        }
    }
};
