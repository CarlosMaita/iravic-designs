<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = Role::create([
                        'name' => 'superadmin',
                        'is_superadmin' => 1,
                        'is_employee' => 1
                    ]);

        $admin = Role::create([
                        'name' => 'admin',
                        'is_superadmin' => 0,
                        'is_employee' => 1
                    ]);

        $permissions = Permission::all();
        $superadmin->allowToMany($permissions);
        $admin->allowToMany($permissions);

        /*
        $permisosEncargadoGraduacion = $permissions->reject(function ($permiso) {
            return (
                    $permiso->name == 'privilegios_ver'
                    || $permiso->name == 'privilegios_crear'
                    || $permiso->name == 'privilegios_editar'
                    || $permiso->name == 'privilegios_eliminar'
                    || $permiso->name == 'usuarios_ver'
                    || $permiso->name == 'usuarios_crear'
                    || $permiso->name == 'usuarios_editar'
                    || $permiso->name == 'usuarios_eliminar'
                    || $permiso->name == 'graduaciones_crear'
                    || $permiso->name == 'graduaciones_editar'
                    || $permiso->name == 'graduaciones_eliminar'
                    || $permiso->name == 'grupos_crear'
                    || $permiso->name == 'grupos_editar'
                    || $permiso->name == 'grupos_eliminar'
                    || $permiso->name == 'personas_crear'
                    || $permiso->name == 'personas_editar'
                    || $permiso->name == 'personas_eliminar'
                    || $permiso->name == 'movimientos_personas_crear'
                    || $permiso->name == 'movimientos_personas_editar'
                    || $permiso->name == 'movimientos_personas_eliminar'
                    || $permiso->name == 'movimientos_graduaciones_ver'
                    || $permiso->name == 'movimientos_graduaciones_crear'
                    || $permiso->name == 'movimientos_graduaciones_editar'
                    || $permiso->name == 'movimientos_graduaciones_eliminar'
            );
        });
        */
    }
}
