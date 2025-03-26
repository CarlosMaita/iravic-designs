<?php

namespace Database\Seeders;

use App\Models\StoreType;
use Illuminate\Database\Seeder;

class StoreTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $storeTypes = [
            [ 'id' => 1, 'name' => 'Deposito'],
            [ 'id' => 2, 'name' => 'Local Comercial'],
            [ 'id' => 3, 'name' => 'Vehículo de Reparto'],
        ];

        foreach ($storeTypes as $storeType) {
            if ($existingStoreType = StoreType::where('id', $storeType['id'])->first()) {
                continue;
            }

            // crealo si no existe
            StoreType::create([ 'id' => $storeType['id'], 'name' =>  $storeType['name']]);
        }

    }
}
