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
            # Permissions
            [
                'name'         => 'view-permission',
                'display_name' => 'Permisos Ver',
                'description'  => 'Permisos Ver',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            # Roles
            [
                'name'         => 'view-role',
                'display_name' => 'Roles Ver',
                'description'  => 'Roles Ver',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'create-role',
                'display_name' => 'Roles Crear',
                'description'  => 'Roles Crear',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'update-role',
                'display_name' => 'Roles Editar',
                'description'  => 'Roles Editar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'delete-role',
                'display_name' => 'Roles Eliminar',
                'description'  => 'Roles Eliminar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            # Users
            [
                'name'         => 'view-user',
                'display_name' => 'Usuarios Ver',
                'description'  => 'Usuarios Ver',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'create-user',
                'display_name' => 'Usuarios Crear',
                'description'  => 'Usuarios Crear',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'update-user',
                'display_name' => 'Usuarios Editar',
                'description'  => 'Usuarios Editar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'delete-user',
                'display_name' => 'Usuarios Eliminar',
                'description'  => 'Usuarios Eliminar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            # Brands
            [
                'name'         => 'view-brand',
                'display_name' => 'Marcas Ver',
                'description'  => 'Marcas Ver',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'create-brand',
                'display_name' => 'Marcas Crear',
                'description'  => 'Marcas Crear',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'update-brand',
                'display_name' => 'Marcas Editar',
                'description'  => 'Marcas Editar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'delete-brand',
                'display_name' => 'Marcas Eliminar',
                'description'  => 'Marcas Eliminar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            # Categories
            [
                'name'         => 'view-category',
                'display_name' => 'Categorías Ver',
                'description'  => 'Categorías Ver',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'create-category',
                'display_name' => 'Categorías Crear',
                'description'   => 'Categorías Crear',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'update-category',
                'display_name' => 'Categorías Editar',
                'description'  => 'Categorías Editar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'delete-category',
                'display_name' => 'Categorías Eliminar',
                'description'  => 'Categorías Eliminar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            # Products
            [
                'name'         => 'view-product',
                'display_name' => 'Productos Ver',
                'description'  => 'Productos Ver',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'create-product',
                'display_name' => 'Productos Crear',
                'description'  => 'Productos Crear',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'update-product',
                'display_name' => 'Productos Editar',
                'description'  => 'Productos Editar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'delete-product',
                'display_name' => 'Productos Eliminar',
                'description'  => 'Productos Eliminar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ];

        Permission::insert($permissions);
    }
}
