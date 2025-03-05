<?php

namespace Database\Seeders;

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
        // Obtener los nombres de permisos existentes
        $existingPermissions = Permission::pluck('name')->toArray();
        
        // definir los permisos 
        $permissions = [
            # Configuration
            [
                'name'         => 'view-configuration',
                'display_name' => 'Configuraciones Ver',
                'description'  => 'Configuraciones Ver',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'create-configuration',
                'display_name' => 'Configuraciones Crear',
                'description'  => 'Configuraciones Crear',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
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
            # Products Transfers
            [
                'name'         => 'view-transfer',
                'display_name' => 'Transferencias Ver',
                'description'  => 'Transferencias Ver',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'create-transfer',
                'display_name' => 'Transferencias Crear',
                'description'  => 'Transferencias Crear',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'update-transfer',
                'display_name' => 'Transferencias Editar',
                'description'  => 'Transferencias Editar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'delete-transfer',
                'display_name' => 'Transferencias Eliminar',
                'description'  => 'Transferencias Eliminar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            # Zones
            [
                'name'         => 'view-zone',
                'display_name' => 'Zonas Ver',
                'description'  => 'Zonas Ver',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'create-zone',
                'display_name' => 'Zonas Crear',
                'description'  => 'Zonas Crear',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'update-zone',
                'display_name' => 'Zonas Editar',
                'description'  => 'Zonas Editar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'delete-zone',
                'display_name' => 'Zonas Eliminar',
                'description'  => 'Zonas Eliminar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'sort-zones',
                'display_name' => 'Zonas Ordenar',
                'description'  => 'Zonas Ordenar',
                'created_at'   => now(),
                'updated_at'   => now()
            ],
            # Customers
            [
                'name'         => 'view-customer',
                'display_name' => 'Clientes Ver',
                'description'  => 'Clientes Ver',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'view-customers-debtors',
                'display_name' => 'Morosos Listar',
                'description'  => 'Morosos Listar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'view-customers-pending-to-schedule',
                'display_name' => 'Clientes pendientes por agendar Listar',
                'description'  => 'Clientes pendientes por agendar Listar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'create-customer',
                'display_name' => 'Clientes Crear',
                'description'  => 'Clientes Crear',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'update-customer',
                'display_name' => 'Clientes Editar',
                'description'  => 'Clientes Editar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'delete-customer',
                'display_name' => 'Clientes Eliminar',
                'description'  => 'Clientes Eliminar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'view-customers-debtors',
                'display_name' => 'Clientes morosos Listar',
                'description'  => 'Clientes morosos Listar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            # Boxes
            [
                'name'         => 'view-box',
                'display_name' => 'Cajas Ver',
                'description'  => 'Cajas Ver',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'create-box',
                'display_name' => 'Cajas Crear',
                'description'  => 'Cajas Crear',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'update-box',
                'display_name' => 'Cajas Editar',
                'description'  => 'Cajas Editar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'delete-box',
                'display_name' => 'Cajas Eliminar',
                'description'  => 'Cajas Eliminar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            # Orders
            [
                'name'         => 'view-order',
                'display_name' => 'Ventas Ver',
                'description'  => 'Ventas Ver',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'create-order',
                'display_name' => 'Ventas Crear',
                'description'  => 'Ventas Crear',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'update-order',
                'display_name' => 'Ventas Editar',
                'description'  => 'Ventas Editar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'delete-order',
                'display_name' => 'Ventas Eliminar',
                'description'  => 'Ventas Eliminar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            # Returns
            [
                'name'         => 'view-refund',
                'display_name' => 'Cambios/Devoluciones Ver',
                'description'  => 'Cambios/Devoluciones Ver',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'create-refund',
                'display_name' => 'Cambios/Devoluciones Crear',
                'description'  => 'Cambios/Devoluciones Crear',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'update-refund',
                'display_name' => 'Cambios/Devoluciones Editar',
                'description'  => 'Cambios/Devoluciones Editar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'delete-refund',
                'display_name' => 'Cambios/Devoluciones Eliminar',
                'description'  => 'Cambios/Devoluciones Eliminar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            # Payments
            [
                'name'         => 'view-payment',
                'display_name' => 'Pagos/Cobros Ver',
                'description'  => 'Pagos/Cobros Ver',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'create-payment',
                'display_name' => 'Pagos/Cobros Crear',
                'description'  => 'Pagos/Cobros Crear',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'update-payment',
                'display_name' => 'Pagos/Cobros Editar',
                'description'  => 'Pagos/Cobros Editar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'delete-payment',
                'display_name' => 'Pagos/Cobros Eliminar',
                'description'  => 'Pagos/Cobros Eliminar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            # Debts
            [
                'name'         => 'view-debt',
                'display_name' => 'Deudas Ver',
                'description'  => 'Deudas Ver',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'create-debt',
                'display_name' => 'Deudas Crear',
                'description'  => 'Deudas Crear',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'update-debt',
                'display_name' => 'Deudas Editar',
                'description'  => 'Deudas Editar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'delete-debt',
                'display_name' => 'Deudas Eliminar',
                'description'  => 'Deudas Eliminar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            # Spendings
            [
                'name'         => 'view-spending',
                'display_name' => 'Gastos Ver',
                'description'  => 'Gastos Ver',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'create-spending',
                'display_name' => 'Gastos Crear',
                'description'  => 'Gastos Crear',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'update-spending',
                'display_name' => 'Gastos Editar',
                'description'  => 'Gastos Editar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'delete-spending',
                'display_name' => 'Gastos Eliminar',
                'description'  => 'Gastos Eliminar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            # Visits
            [
                'name'         => 'view-visit',
                'display_name' => 'Visitas Ver',
                'description'  => 'Visitas Ver',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'create-visit',
                'display_name' => 'Visitas Crear',
                'description'  => 'Visitas Crear',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'update-visit',
                'display_name' => 'Visitas Editar',
                'description'  => 'Visitas Editar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'delete-visit',
                'display_name' => 'Visitas Eliminar',
                'description'  => 'Visitas Eliminar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'complete-visit',
                'display_name' => 'Visitas Completar',
                'description'  => 'Visitas Marcar como Visitas y desmarcarlas',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'update-responsable-visit',
                'display_name' => 'Visitas Actualizar responsable',
                'description'  => 'Visitas Actualizar responsable',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            # Schedules
            [
                'name'         => 'view-schedule',
                'display_name' => 'Agendas Ver',
                'description'  => 'Agendas Ver',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'update-schedule',
                'display_name' => 'Agendas Editar',
                'description'  => 'Agendas Editar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'delete-schedule',
                'display_name' => 'Agendas Eliminar',
                'description'  => 'Agendas Eliminar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'view-customers-pending-to-schedule',
                'display_name' => 'Clientes pendientes por agendar Listar',
                'description'  => 'Clientes pendientes por agendar Listar',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            # Feature - Precios por Metodo de pago 
            [
                'name'         => 'prices-per-method-payment',
                'display_name' => 'Precios por Método de Pago',
                'description'  => 'Precios por Método de Pago',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            # Credit
            [
                'name'         => 'view-credit',
                'display_name' => 'Creditos Ver',
                'description'  => 'Creditos Ver',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ];


        // Verificar y crear los permisos
        foreach ($permissions as $permission) {
            if (!in_array($permission['name'], $existingPermissions)) {
                Permission::create($permission);
            }
        }

        // Remover permisos no encontrados
        $permissions = array_column($permissions, 'name');
        foreach ($existingPermissions as $existingPermission) {
            if (!in_array($existingPermission, $permissions)) {
                Permission::where('name', $existingPermission)->delete();
            }
        }

    }
}
