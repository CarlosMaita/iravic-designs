<?php

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colors = [
            # Colors
            [
                'name' => 'Amarillo',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Azul',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Blanco',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Marron',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Negro',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Rojo',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Verde',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        Color::insert($colors);
    }
}
