<?php

return [
    'advanced_search' => [
        'advanced_search' => 'Búsqueda Avanzada',
        'clear_filter'    => 'Limpiar filtro'
    ],
    'boxes-sales' => [
        'boxes' => [
            'index'             => 'Listado de Cajas',
            'create'            => 'Crear nueva Caja',
            'edit'              => 'Editar Caja',
            'box'               => 'Caja',
            'cash_initial'      => 'Efectivo inicial',
            'closed'            => 'Cerrada',
            'date'              => 'Fecha',
            'date_start'        => 'Fecha Inicio',
            'date_end'          => 'Fecha Fin',
            'sure_to_close_box' => 'Seguro de cerrar la caja',
            'total_bankwire'    => 'Total Transferencia',
            'total_card'        => 'Total Tarjeta',
            'total_cash'        => 'Total Efectivo',
            'total_credit'      => 'Total Crédito',
            'total_payed'       => 'Total Pagado',
            'user'              => 'Usuario'
        ],
        'orders' => [
            'index'             => 'Listado de Pedidos',
            'create'            => 'Crear nuevo Pedido',
            'edit'              => 'Editar Pedido',
            'order'             => 'Pedido',
            'id'                => 'ID',
            'box_id'            => 'ID Caja',
            'customer'          => 'Cliente',
            'date'              => 'Fecha',
            'products'          => [
                'index'         => 'Productos',
                'name'          => 'Nombre',
                'color'         => 'Color',
                'qty'           => 'Cant.',
                'size'          => 'Tamaño',
                'total'         => 'Total'
            ],
            'total'             => 'Total',
            'user'              => 'Usuario'
        ]
    ],
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
    'customers-management' => [
        'customers' => [
            'index'     => 'Listado de Clientes',
            'create'    => 'Crear nuevo cliente',
            'details'   => 'Detalle cliente',
            'edit'      => 'Editar cliente',
            'name'      => 'Nombre',
            'dni'       => 'C.I',
            'telephone'     => 'Teléfono',
            'qualification' => 'Calificación',
            'zone'      => 'Zona',
        ],
        'zones' => [
            'index'     => 'Listado de Zonas',
            'create'    => 'Crear nueva zona',
            'edit'      => 'Editar zona'
        ]
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
        'cancel'        => 'Cancelar',
        'create'        => 'Crear',
        'edit'          => 'Editar',
        'save'          => 'Guardar',
        'update'        => 'Actualizar',
        'fields' => [
            'boxes' => [
                'cash_initial' => 'Efectivo inicial'
            ],
            'customers' => [
                'address' => 'Dirección',
                'contact_name' => 'Nombre de contacto',
                'contact_telephone' => 'Teléfono de contacto',
                'contact_dni' => 'C.I de contacto',
                'dni' => 'C.I',
                'dni_picture' => 'Foto C.I',
                'max_credit' => 'Crédito máximo',
                'name' => 'Nombre',
                'qualification' => 'Calificación',
                'receipt_picture' => 'Foto recibo',
                'telephone' => 'Teléfono ',
                'zone' => 'Zona'
            ],
            'general' => [
                'name' => 'Nombre',
            ],
            'orders' => [
                'customer'  => 'Cliente',
                'finish'    => 'Finalizar',
                'payment'   => 'Pago',
                'products'  => 'Productos',
                'product'   => 'Producto',
                'confirmar' => 'Confirmar',
                'bankwire'  => 'Transferencia',
                'card'      => 'Tarjeta',
                'cash'      => 'Efectivo',
                'credit'    => 'Crédito',
                'customer_information' => 'Información de Cliente',
                'products_information' => 'Información de Productos',
                'payment_information' => 'Información de Pago'
            ],
            'products' => [
                'brand'             => 'Marca',
                'category'          => 'Categoría',
                'code'              => 'Código',
                'color'             => 'Color',
                'combinations'      => 'Combinaciones',
                'gender'            => 'Género',
                'is_regular'        => 'Es un producto regular?',
                'is_price_generic'  => 'Precio genérico',
                'price'             => 'Precio',
                'size'              => 'Talla',
            ],
            'users' => [
                'email' => 'Correo electrónico',
                'name'  => 'Nombre',
                'password'  => 'Contraseña',
                'confirm_password'  => 'Confirmar Contraseña',
                'role'  => 'Rol'
            ]
        ],
        'labels' => [
            'customer_address_info' => 'Dirección y Ubicación del cliente',
            'customer_contact_info' => 'Información Persona de Contacto',
            'customer_finance_info' => 'Información Financiera',
            'customer_personal_info' => 'Información Personal',
            'customer_selected' => 'Información Cliente Seleccionado',
            'Complete each step until you reach the end' => 'Complete cada paso hasta llegar al final'
        ]
    ],
    'general' => [
        'close'             => 'Cerrar',
        'delete_resource'   => '¿Seguro que desea eliminar este recurso?',
        'home'              => 'Inicio',
        'new_a'             => 'Nueva',
        'new_o'             => 'Nuevo',
        'next'              => 'Siguiente',
        'no'                => 'No',
        'previous'          => 'Anterior',
        'operation_error'   => 'Ha ocurrido un error al tratar de realizar la operación en este momento.',
        'search'            => 'Buscar',
        'yes'               => 'Si'
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
        'boxes'                 => 'Cajas',
        'boxes-sales'           => 'Cajas y Ventas',
        'brands'                => 'Marcas',
        'catalog'               => 'Catálogo',
        'categories'            => 'Categorías',
        'customers-management'  => 'Gestión Clientes',
        'customers'             => 'Clientes',
        'orders'                => 'Pedidos',
        'poducts'               => 'Productos',
        'settings'              => 'Configuración',
        'users'                 => 'Usuarios',
        'roles'                 => 'Roles',
        'permissions'           => 'Permisos',
        'privileges'            => 'Privilegios',
        'zones'                 => 'Zonas'
    ],
];