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
            'email' => 'admin@email.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin'),
            'remember_token' => Str::random(10)
        ]);
        
        $user->assignRole($role_admin);
        
        /*
        $numberOfUsers = 10;
        $faker = Faker::create();
        for ($i = 0; $i<$numberOfUsers; $i++) {
            $user = User::create([ 
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'menuroles' => 'user'
            ]);
            $user->assignProfile('user');
        }
        */
    }
}
