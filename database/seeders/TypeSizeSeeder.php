<?php

namespace Database\Seeders;

use App\Constants\BaseCategoryConstants;
use App\Constants\GenderConstants;
use App\TypeSize;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class TypeSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        TypeSize::truncate();
        $type_sizes = [ 
            [
                "id" => 1,
                "name" => "Tallas para ropa niños",
                "base_category_id" => BaseCategoryConstants::ROPA,
                "genders" => implode(',', [GenderConstants::NINO, GenderConstants::NINIA , GenderConstants::NINOS_UNISEX]),
            ],
            // Las tallas para bebes fue removida, se deja el id=7 Obsoleto!
            [
                "id" => 2,
                "name" => "Tallas para accesorios",
                "base_category_id" => BaseCategoryConstants::ACCESORIOS,
                "genders" => implode(',', GenderConstants::ALL),
            ],  
        ];
        // Loop through each color
        foreach ($type_sizes as $type_size) {
            // If the color already exists in the database, skip it
            if ($existingTypeSize = TypeSize::where('id', $type_size['id'])->first()) {
                continue;
            }

            // If the color doesn't exist, create it
            TypeSize::create([
                'id' => $type_size['id'],
                'name' => $type_size['name'],
                'base_category_id' => $type_size['base_category_id'],
                'genders' => $type_size['genders'] ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
