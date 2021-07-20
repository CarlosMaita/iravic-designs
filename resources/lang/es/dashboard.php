<?php

return [
    'breadcrumb' => [
        'home' => 'Inicio',
    ],
    'catalog' => [
        'categories' => [
            'index'     => 'Listado de Categorías',
            'create'    => 'Crear nueva categoría',
            'edit'      => 'Editar categoría'
        ],
        'brands' => [
            'index'     => 'Listado de Marcas',
            'create'    => 'Crear nueva marca',
            'edit'      => 'Editar marca'
        ],
        'products' => [
            'index'     => 'Listado de Productos',
            'create'    => 'Crear nuevo producto',
            'edit'      => 'Editar producto'
        ],
    ],
    'config' => [
        'roles' => [
            'index'             => 'Listado de Roles',
            'create'            => 'Crear nuevo rol',
            'edit'              => 'Editar rol',
            'name'              => 'Nombre',
            'description'       => 'Descripción',
            'is_employee'       => 'Es empleado',
            'permissions_label' => 'Seleccione permisos para el rol'
        ],
        'permissions' => [
            'index'         => 'Listado de Permisos',
            'name'          => 'Nombre',
            'display_name'  => 'Nombre a mostrar',
            'description'   => 'Descripción',
        ],
        'users' => [
            'index'     => 'Listado de Usuarios',
            'create'    => 'Crear nuevo usuario',
            'edit'      => 'Editar usuario',
            'email'     => 'Email',
            'name'      => 'Nombre',
            'role'      => 'Role',
        ]
    ],
    'footer' => [
        'developed by' => 'Desarrollado por'
    ],
    'form' => [
        'back'          => 'Atrás',
        'back to list'  => 'Ir al listado',
        'create'        => 'Crear',
        'save'          => 'Guardar',
        'update'        => 'Actualizar',
        'fields' => [
            'general' => [
                'name' => 'Nombre',
            ],
            'products' => [
                'brand'         => 'Marca',
                'category'      => 'Categoría',
                'code'          => 'Código',
                'color'         => 'Color',
                'combinations'  => 'Combinaciones',
                'gender'        => 'Género',
                'is_regular'    => 'Es un producto regular?',
                'size'          => 'Talla',
            ]
        ]
    ],
    'general' => [
        'delete_resource'   => '¿Seguro que desea eliminar este recurso?',
        'home'              => 'Inicio',
        'new_a'             => 'Nueva',
        'new_o'             => 'Nuevo',
        'operation_error'   => 'Ha ocurrido un error al tratar de realizar la operación en este momento.'
    ],
    'header' => [
        'profile'   => 'Perfil',
        'settings'  => 'Configuración',
    ],
    'my-profile' => [
        'edit'                      => 'Editar mi perfil',
        'enter-current-password'    => 'Ingresa tu contraseña actual',
        'info-password'             => 'Por seguridad, debera ingresar su contraseña cada vez que realice un cambio.'
    ],
    'sidebar' => [
        'catalog'     => 'Catálogo',
        'categories'  => 'Categorías',
        'brands'      => 'Marcas',
        'poducts'     => 'Productos',
        'settings'    => 'Configuración',
        'users'       => 'Usuarios',
        'roles'       => 'Roles',
        'permissions' => 'Permisos',
        'privileges'  => 'Privilegios'
    ],
];