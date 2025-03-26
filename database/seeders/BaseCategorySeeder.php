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
                'name' => 'Calzado',
                'has_gender' => true,
                'has_size' => true
            ],
            [
                'name' => 'Accesorios',
                'has_gender' => true,
                'has_size' => true
            ],
            [
                'name' => 'Sillones',
                'has_gender' => false,
                'has_size' => false
            ],
            [
                'name' => 'Sommier/colchones',
                'has_gender' => false,
                'has_size' => false
            ],
            [
                'name' => 'Dormitorio',
                'has_gender' => false,
                'has_size' => false
            ],
            [
                'name' => 'Sala',
                'has_gender' => false,
                'has_size' => false
            ],            
            [
                'name' => 'Baño',
                'has_gender' => false,
                'has_size' => false
            ],
            [
                'name' => 'Almohadas',
                'has_gender' => false,
                'has_size' => false
            ],            
            [
                'name' => 'Comedor',
                'has_gender' => false,
                'has_size' => false
            ],
            [
                'name' => 'Espejos',
                'has_gender' => false,
                'has_size' => false
            ],
            [
                'name' => 'Juguetes',
                'has_gender' => false,
                'has_size' => false
            ],
            [
                'name' => 'Exterior/ Jardín',
                'has_gender' => false,
                'has_size' => false
            ]
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
