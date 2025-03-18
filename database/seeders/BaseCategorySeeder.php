<?php

namespace Database\Seeders;

use App\Models\BaseCategory;
use Illuminate\Database\Seeder;

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
            'Ropa',
            'Calzado',
            'Accesorios', 
            'Sillones',
            'Sommier/colchones',
            'Dormitorio',
            'Sala',
            'Baño',
            'Almohadas',
            'Comedor',
            'Espejos',
            'Juguetes',
            'Exterior/ Jardín'
        ];

        foreach ($baseCategories as $category) {
            if (BaseCategory::where('name', $category)->first()) {
                continue;
            }

            BaseCategory::create([
                'name' => $category
            ]);
        }

    }
}
