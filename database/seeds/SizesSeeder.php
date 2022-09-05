<?php

use App\Models\Size;
use Illuminate\Database\Seeder;

class SizesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sizes = [
            # Sizes
            [
                'name' => '32',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => '33',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => '34',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => '35',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => '36',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => '37',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => '38',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        Size::insert($sizes);
    }
}
