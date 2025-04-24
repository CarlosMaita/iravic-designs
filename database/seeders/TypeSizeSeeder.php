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
                "name" => "Tallas para calzado hombre adulto",
                "base_category_id" => BaseCategoryConstants::CALZADO,
                "genders" => GenderConstants::ADULTO_HOMBRE,
            ],
            [
                "id" => 2,
                "name" => "Tallas para calzado mujer adulta",
                "base_category_id" => BaseCategoryConstants::CALZADO,
                "genders" => GenderConstants::ADULTO_MUJER,

            ],
            [
                "id" => 3,
                "name" => "Tallas para calzado unisex adulto",
                "base_category_id" => BaseCategoryConstants::CALZADO,
                "genders" => GenderConstants::ADULTO_UNISEX
            ],
            [
                "id" => 4,
                "name" => "Tallas para calzado niños",
                "base_category_id" => BaseCategoryConstants::CALZADO,
                "genders" => implode(',', [GenderConstants::NINO, GenderConstants::NINIA , GenderConstants::NINOS_UNISEX]),
            ],
            [
                "id" => 5,
                "name" => "Tallas para ropa adultos",
                "base_category_id" => BaseCategoryConstants::ROPA,
                "genders" => implode(',', [GenderConstants::ADULTO_HOMBRE, GenderConstants::ADULTO_MUJER , GenderConstants::ADULTO_UNISEX]),
            ],
            [
                "id" => 6,
                "name" => "Tallas para ropa niños",
                "base_category_id" => BaseCategoryConstants::ROPA,
                "genders" => implode(',', [GenderConstants::NINO, GenderConstants::NINIA , GenderConstants::NINOS_UNISEX]),
            ],
            // Las tallas para bebes fue removida, se deja el id=7 Obsoleto!
            [
                "id" => 8,
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
