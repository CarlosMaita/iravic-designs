<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder adds colors to the database if they don't already exist.
     *
     * @return void
     */
    public function run()
    {
        // Define the colors to be added
        $colors = [
            'Amarillo',
            'Azul',
            'Blanco',
            'Marron',
            'Negro',
            'Rojo',
            'Verde',
            'Mostaza',
            'Nude',
            'Terracota',
            'Chocolate',
            'Tabaco',
            'Havana',
            'Naranja',
            'Celeste',
            'Dorado',
            'Beige',
            'Rosa',
            'Turquesa',
            'Violeta',
            'Salmón',
            'Gris'
        ];

        // Loop through each color
        foreach ($colors as $color) {
            // If the color already exists in the database, skip it
            if ($existingColor = Color::where('name', $color)->first()) {
                continue;
            }

            // If the color doesn't exist, create it
            Color::create([
                'name' => $color,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
