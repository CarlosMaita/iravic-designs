<?php

use App\Models\Permission;
use App\Models\Role;
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

        $this->call('UsersSeeder');
    }
}
