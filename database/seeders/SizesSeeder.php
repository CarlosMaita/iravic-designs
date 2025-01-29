<?php

namespace Database\Seeders;

use App\Constants\TypeSizeConstants;
use App\Models\Size;
use App\TypeSize;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SizesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Size::truncate();

        $sizes_talla_calzado_adultos_hombre = range(39, 46, 0.5);
        $sizes_talla_calzado_adultos_mujer = range(34, 41, 0.5);
        $sizes_talla_calzado_adultos_unisex = range(34, 46, 0.5);;
        $sizes_talla_calzado_ninos = range(12, 38, 0.5);
        $sizes_talla_ropa_adultos = [ "xxs", "xs", "s", "m", "l", "xl", "xxl" ];
        $sizes_talla_ropa_ninos = range(4, 16, 2);
        $sizes_talla_ropa_bebes = range(0, 6, 2); ;
        $sizes_talla_accesorios = [ 
            "xs", "s", "m", "l", "xl", "xxl", 
            "1" , "2" , "3" , "4" , "5" , "6" , 
            "7" , "8" , "9" , "10" , "11" , "12",
            "13" , "14" ];

        
            
        foreach ($sizes_talla_calzado_adultos_hombre as $size) {
            $sizes[] = [
                'name' => $size,
                'type_size_id' => TypeSizeConstants::TYPE_SIZE_CALZADO_ADULTOS_HOMBRE,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        foreach ($sizes_talla_calzado_adultos_mujer as $size) {
            $sizes[] = [
                'name' => $size,
                'type_size_id' => TypeSizeConstants::TYPE_SIZE_CALZADO_ADULTOS_MUJER,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        foreach ($sizes_talla_calzado_adultos_unisex as $size) {
            $sizes[] = [
                'name' => $size,
                'type_size_id' => TypeSizeConstants::TYPE_SIZE_CALZADO_ADULTOS_UNISEX,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        foreach ($sizes_talla_calzado_ninos as $size) {
            $sizes[] = [
                'name' => $size,
                'type_size_id' => TypeSizeConstants::TYPE_SIZE_CALZADO_NINOS,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        foreach ($sizes_talla_ropa_adultos as $size) {
            $sizes[] = [
                'name' => $size,
                'type_size_id' => TypeSizeConstants::TYPE_SIZE_ROPA_ADULTOS,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        foreach ($sizes_talla_ropa_ninos as $size) {
            $sizes[] = [
                'name' => $size,
                'type_size_id' => TypeSizeConstants::TYPE_SIZE_ROPA_NINOS,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        foreach ($sizes_talla_ropa_bebes as $size) {
            $sizes[] = [
                'name' => $size,
                'type_size_id' => TypeSizeConstants::TYPE_SIZE_ROPA_BEBES,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        foreach ($sizes_talla_accesorios as $size) {
            $sizes[] = [
                'name' => $size,
                'type_size_id' => TypeSizeConstants::TYPE_SIZE_ACCESORIOS,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        Size::insert($sizes);

        


       

    }
}
