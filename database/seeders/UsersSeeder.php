<?php

namespace Database\Seeders;

use App\User;
use App\Models\Role;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = Role::where('name', 'superadmin')->first();
        /*  insert users   */
        $user = User::create([ 
            'name' => 'admin',
            'email' => 'carlosmaita2009@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('Venezuela'), // password
            'remember_token' => Str::random(10)
        ]);
        
        $user->assignRole($role_admin);
        
    }
}
