<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Size;
use App\Models\StoreType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (!Permission::first()) {
            $this->call(PermissionsSeeder::class);
        }

        if (!Role::first()) {
            $this->call(RolesSeeder::class);
        }

        $this->call(UsersSeeder::class);

        if (!Color::first()) {
            $this->call(ColorsSeeder::class);
        }

        if (!Size::first()) {
            $this->call(SizesSeeder::class);
        }

        $this->call(BaseCategorySeeder::class);

        if (!StoreType::first()) {
            $this->call(StoreTypesSeeder::class);
        }
    }
}
