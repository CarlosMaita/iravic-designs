<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AddSlugToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // Poblar slugs para registros existentes
        $categories = DB::table('categories')->get();
        foreach ($categories as $category) {
            $slug = Str::slug($category->name);
            // Asegurar unicidad
            $originalSlug = $slug;
            $i = 1;
            while (DB::table('categories')->where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $i;
                $i++;
            }
            DB::table('categories')->where('id', $category->id)->update(['slug' => $slug]);
        }

        // Ahora sí, agregar el índice único
        Schema::table('categories', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
