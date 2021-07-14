<?php

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            # Permisos
            [
                'name'        => 'view-permission',
                'display_name' => 'Permisos Ver',
                'description' => 'Permisos Ver',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            # Roles
            [
                'name'        => 'view-role',
                'display_name' => 'Roles Ver',
                'description' => 'Roles Ver',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'         => 'create-role',
                'display_name' => 'Roles Crear',
                'description' => 'Roles Crear',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'update-role',
                'display_name' => 'Roles Editar',
                'description' => 'Roles Editar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'delete-role',
                'display_name' => 'Roles Eliminar',
                'description' => 'Roles Eliminar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            #
            [
                'name'        => 'view-user',
                'display_name' => 'Usuarios Ver',
                'description' => 'Usuarios Ver',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'         => 'create-user',
                'display_name' => 'Usuarios Crear',
                'description' => 'Usuarios Crear',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'update-user',
                'display_name' => 'Usuarios Editar',
                'description' => 'Usuarios Editar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'delete-user',
                'display_name' => 'Usuarios Eliminar',
                'description' => 'Usuarios Eliminar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ];

        Permission::insert($permissions);
    }
}
