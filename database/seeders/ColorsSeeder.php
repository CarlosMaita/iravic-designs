<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

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
            ['id' => 1, 'name' => 'Amarillo', 'code' => '#F7DC6F'],
            ['id' => 2, 'name' => 'Azul', 'code' => '#4682B4'],
            ['id' => 3, 'name' => 'Blanco', 'code' => '#FFFFFF'],
            ['id' => 4, 'name' => 'Marron', 'code' => '#786C3B'],
            ['id' => 5, 'name' => 'Negro', 'code' => '#000000'],
            ['id' => 6, 'name' => 'Rojo', 'code' => '#FF3737'],
            ['id' => 7, 'name' => 'Verde', 'code' => '#8BC34A'],
            ['id' => 8, 'name' => 'Mostaza', 'code' => '#F2C464'],
            ['id' => 9, 'name' => 'Nude', 'code' => '#F5F5DC'],
            ['id' => 10, 'name' => 'Terracota', 'code' => '#DA70D6'],
            ['id' => 11, 'name' => 'Chocolate', 'code' => '#964B00'],
            ['id' => 12, 'name' => 'Tabaco', 'code' => '#A52A2A'],
            ['id' => 13, 'name' => 'Havana', 'code' => '#663300'],
            ['id' => 14, 'name' => 'Naranja', 'code' => '#FFC107'],
            ['id' => 15, 'name' => 'Celeste', 'code' => '#87CEEB'],
            ['id' => 16, 'name' => 'Dorado', 'code' => '#F8E231'],
            ['id' => 17, 'name' => 'Beige', 'code' => '#F5DEB3'],
            ['id' => 18, 'name' => 'Rosa', 'code' => '#FFC0CB'],
            ['id' => 19, 'name' => 'Turquesa', 'code' => '#1ABC9C'],
            ['id' => 20, 'name' => 'Violeta', 'code' => '#8E24AA'],
            ['id' => 21, 'name' => 'Salmón', 'code' => '#FFA07A'],
            ['id' => 22, 'name' => 'Gris', 'code' => '#808080']
        ];

        // Delete all colors in the database
        Schema::disableForeignKeyConstraints();
        Color::truncate();

        // Loop through each color
        foreach ($colors as $color) {
            // Create the color
            Color::create([
                'id' => $color['id'],
                'name' => $color['name'],
                'code' => $color['code'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
