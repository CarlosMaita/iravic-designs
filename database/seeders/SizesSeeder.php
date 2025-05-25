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

        // $sizes_talla_ropa_adultos = [ "XXS", "XS", "S", "M", "L", "XL", "XXL" ];
        $sizes_talla_ropa_ninos = range(0, 16, 2);
        $sizes_talla_accesorios = [ 
            "XXS", "XS", "S", "M", "L", "XL", "XXL",
            "1" , "2" , "3" , "4" , "5" , "6" , 
            "7" , "8" , "9" , "10" , "11" , "12",
            "13" , "14" ];
        $sizes_talla_sin_talla =[
            "sin talla"
        ];
     
        foreach ($sizes_talla_ropa_ninos as $size) {
            $sizes[] = [
                'name' => $size,
                'type_size_id' => TypeSizeConstants::TYPE_SIZE_ROPA_NINOS,
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
        foreach ($sizes_talla_sin_talla as $size) {
            $sizes[] = [
                'name' => $size,
                'type_size_id' => null,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }


        Size::insert($sizes);
    }
}
