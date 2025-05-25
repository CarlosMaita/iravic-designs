<?php

namespace Database\Seeders;

use App\Models\BaseCategory;
use FontLib\Table\Type\name;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class BaseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $baseCategories = [
            [
                'name' => 'Ropa',
                'has_gender' => true,
                'has_size' => true
            ],
            [
                'name' => 'Accesorios',
                'has_gender' => true,
                'has_size' => true
            ],
        ];

        Schema::disableForeignKeyConstraints();
        BaseCategory::truncate();

        foreach ($baseCategories as $category) {
            if (BaseCategory::where('name', $category['name'])->first()) {
                continue;
            }

            BaseCategory::create([
                'name' => $category['name'],
                'has_gender' => $category['has_gender'] ,
                'has_size' => $category['has_size']
            ]);
        }

    }
}
