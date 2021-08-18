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

        $local = Role::create([
            'name' => 'Local',
            'is_superadmin' => 0,
            'is_employee' => 1
        ]);

        $truck = Role::create([
            'name' => 'Camión',
            'is_superadmin' => 0,
            'is_employee' => 1
        ]);

        $moto = Role::create([
            'name' => 'Moto',
            'is_superadmin' => 0,
            'is_employee' => 1
        ]);

        $permissions = Permission::all();
        $othersPermissions = $this->getOthersPermissions($permissions);

        $superadmin->allowToMany($permissions);
        $admin->allowToMany($permissions);
        $local->allowToMany($othersPermissions);
        $truck->allowToMany($othersPermissions);
        $moto->allowToMany($othersPermissions);
    }

    private function getOthersPermissions($permissions)
    {
        return $permissions->reject(function ($permission) {
            return (
                $permission->name == 'view-permission'
                || $permission->name == 'view-role'
                || $permission->name == 'create-role'
                || $permission->name == 'update-role'
                || $permission->name == 'delete-role'
                || $permission->name == 'view-user'
                || $permission->name == 'create-user'
                || $permission->name == 'update-user'
                || $permission->name == 'delete-user'
                || $permission->name == 'create-brand'
                || $permission->name == 'update-brand'
                || $permission->name == 'delete-brand'
                || $permission->name == 'create-category'
                || $permission->name == 'update-category'
                || $permission->name == 'delete-category'
                || $permission->name == 'create-zone'
                || $permission->name == 'update-zone'
                || $permission->name == 'delete-zone'
                || $permission->name == 'update-responsable-visit'
                || $permission->name == 'delete-schedule'
            );
        });
    }
}
