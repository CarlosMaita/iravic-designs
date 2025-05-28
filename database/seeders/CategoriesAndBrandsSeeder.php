<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesAndBrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Chaquetas',
                'base_category_id' => 1,
            ],
            [
                'name' => 'Faldas',
                'base_category_id' => 1,
            ],
            [
                'name' => 'Short',
                'base_category_id' => 1,
            ],
            [
                'name' => 'Leggins',
                'base_category_id' => 1,
            ],
            [
                'name' => 'Blusas',
                'base_category_id' => 1,
            ],
            [
                'name' => 'Franelas',
                'base_category_id' => 1,
            ],
            [
                'name' => 'Patalones',
                'base_category_id' => 1,
            ],
            [
                'name' => 'Pijamas',
                'base_category_id' => 1,
            ],
            [
                'name' => 'Vestidos',
                'base_category_id' => 1,
            ],
            [
                'name' => 'Accesorios',
                'base_category_id' => 2,
            ],
        ];

        $brands = [ 
            [
                'name' => 'Iravic',
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
        foreach ($brands as $brand) {
            \App\Models\Brand::create($brand);
        }
    }
}
