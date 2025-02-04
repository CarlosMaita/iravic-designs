<?php

return [
    'advanced_search' => [
        'advanced_search' => 'Búsqueda Avanzada',
        'clear_filter'    => 'Limpiar filtro'
    ],
    'boxes' => [
        'index'             => 'Listado de Cajas',
        'create'            => 'Crear nueva Caja',
        'edit'              => 'Editar Caja',
        'box'               => 'Caja',
        'cash_initial'      => 'Efectivo inicial',
        'cash_in_box'       => 'Efectivo en caja',
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
        'total_refunded'    => 'Total Devuelto',
        'total_spent'       => 'Total Gastado',
        'total_charges'     => 'Total en Pagos/Cobros',
        'total_final_sales' => 'Total en Ventas',
        'user'              => 'Usuario'
    ],
    'breadcrumb' => [
        'home' => 'Inicio',
        'dashboard' => 'Panel de Control'
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
            'edit'      => 'Editar producto',
            'product_image' => [
                'image_not_accepted' => 'Formato de imagen no aceptado. Solo se permiten imagenes con extensiones jpg, png, jpeg o webp',
                'image_deleted' => 'Imagen eliminada con éxito',
                'image_not_found' => 'Imagen no encontrada',
            ]
        ],
        'inventory' => [
            'index'     => 'Listado de Inventario',
        ],
    
    ],
    'customers' => [
        'balance'               => 'Saldo',
        'create'                => 'Crear nuevo cliente',
        'details'               => 'Detalle cliente',
        'dni'                   => 'C.I',
        'days_to_notify_debt'   => 'Días después del último pago para aparecer en la lista de clientes morosos',
        'edit'                  => 'Editar cliente',
        'help_last_payment'     => 'El último registro corresponde a la fecha de la última transacción registrada, ya sea un pago, una deuda o una venta.',
        'help_days_to_notify_debt' => 'Si no se ha realizado el primer pago, se consideran los dias para la última transacción registrada una deuda o una venta.',
        'index'                 => 'Listado de Clientes',
        'last_payment'          => 'Último registro',
        'name'                  => 'Nombre',
        'qualification'         => 'Calificación',
        'telephone'             => 'Teléfono',
        'total_buyed'           => 'Total comprado',
        'total_credit_give_for' => 'Total crédito dado',
        'total_payments'        => 'Total pagos',
        'total_refund_credit'   => 'Total crédito devuelto',
        'total_debts'           => 'Total deudas',
        'zone'                  => 'Zona',
        
    ],
    'config' => [
        'index' => 'Listado de configuraciones',
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
        'accept'        => 'Aceptar',
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
            'config' => [
                'discount_password' => 'Contraseña para descuentos'
            ],
            'customers' => [
                'address'             => 'Dirección',
                'address_picture'     => 'Foto del frente de la casa',
                'contact_name'        => 'Nombre de contacto',
                'contact_telephone'   => 'Teléfono de contacto',
                'contact_dni'         => 'C.I de contacto',
                'days_to_notify_debt' => 'Días después del último pago para aparecer en la lista de clientes morosos',
                'dni'                 => 'C.I',
                'dni_picture'         => 'Foto C.I',
                'max_credit'          => 'Crédito máximo',
                'name'                => 'Nombre',
                'email'               => 'Correo electrónico',
                'qualification'       => 'Calificación',
                'receipt_picture'     => 'Foto recibo',
                'cellphone'           => 'Teléfono celular',
                'telephone'           => 'Teléfono',
                'total_debt'          => 'Deuda total',
                'zone'                => 'Zona',
                'card_front'          => 'Tarjeta Frente',
                'card_back'           => 'Tarjeta Dorso'
            ],
            'base_categories' => [ 
                'name' => 'Nombre',
                'base_category' => 'Categoría Base'
            ],
            'debts' => [
                'amount'            => 'Monto',
                'comment'           => 'Comentario',
                'customer'          => 'Cliente'
            ],
            'general' => [
                'name'              => 'Nombre',
                'amount_quotas'     => 'Nº Cuotas',
                'frequency'         => 'Frecuencia',
                'start_date'        => 'Fecha de inicio'
            ],
            'orders' => [
                'customer'              => 'Cliente',
                'finish'                => 'Finalizar',
                'payment'               => 'Pago',
                'product'               => 'Producto',
                'products'              => 'Productos',
                'products_refund'       => 'Devolver',
                'products_change'       => 'Llevar',
                'confirmar'             => 'Confirmar',
                'bankwire'              => 'Transferencia',
                'card'                  => 'Tarjeta',
                'cash'                  => 'Efectivo',
                'credit'                => 'Crédito',
                'customer_information'  => 'Información de Cliente',
                'products_information'  => 'Información de Productos',
                'payment_information'   => 'Información de Pago'
            ],
            'payments' => [
                'amount'            => 'Monto a pagar',
                'amount_collection' => 'Monto cobrado',
                'comment'           => 'Comentario',
                'customer'          => 'Cliente',
                'payment_method'    => 'Método de pago',
                'suggested_collection_amount' => 'Monto de cobro sugerido'
            ],
            'products' => [
                'brand'             => 'Marca',
                'category'          => 'Categoría',
                'code'              => 'Código',
                'color'             => 'Color',
                'combinations'      => 'Combinaciones',
                'gender'            => 'Género',
                'is_regular'        => 'Es un producto regular?',
                'price'             => 'Precio',
                'price-from'        => 'Precio desde',
                'price-to'          => 'Precio hasta',
                'size'              => 'Talla',
                'available'         => 'Disponible',
                'qty'               => 'Cantidad'
            ],
            'spendings' => [
                'id'      => 'ID',
                'date'    => 'Fecha',
                'amount'  => 'Monto',
                'comment' => 'Comentario',
                'picture' => 'Foto'
            ],
            'users' => [
                'email' => 'Correo electrónico',
                'name'  => 'Nombre',
                'password'  => 'Contraseña',
                'confirm_password'  => 'Confirmar Contraseña',
                'role'  => 'Rol'
            ],
            'visits' => [
                'comment' => 'Comentario',
                'date'  => 'Fecha',
                'suggested_collection' => 'Cobro sugerido',
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
    'orders' => [
        'index'             => 'Listado de ventas',
        'create'            => 'Crear nueva venta',
        'edit'              => 'Editar venta',
        'order'             => 'Venta',
        'id'                => 'ID',
        'box_id'            => 'ID Caja',
        'customer'          => 'Cliente',
        'date'              => 'Fecha',
        'payment_method'    => 'Método de pago',
        'products'          => [
            'index'         => 'Productos',
            'name'          => 'Nombre',
            'color'         => 'Color',
            'qty'           => 'Cant.',
            'size'          => 'Talla',
            'total'         => 'Total'
        ],
        'discount'          => 'Descuento',
        'subtotal'          => 'Subtotal',
        'total'             => 'Total',
        'user'              => 'Usuario'
    ],
    'collections' => [
        'index'             => 'Listado de colecciones',
        'create'            => 'Crear nueva colección',
        'edit'              => 'Editar colección',
        'id'                => 'ID',
        'frequency'         => 'Frecuencia',
        'customer'          => 'Cliente',
        'balance'           => 'Por Pagar',
        'start_date'        => 'Fecha de Inicio',
        'amount_quotas'     => 'N° Cuotas',
        'quotas'            => 'Monto por Cuotas',
        'total'             => 'Deuda Total',
    ],
    'credits' => [
        'index'             => 'Listado de créditos',
        'create'            => 'Crear nuevo crédito',
        'edit'              => 'Editar crédito',
        'id'                => 'ID',
        'frequency'         => 'Frecuencia',
        'customer'          => 'Cliente',
        'start_date'        => 'Fecha de Inicio',
        'amount_quotas'     => 'N° Cuotas',
        'quotas'            => 'Monto por Cuotas',
        'total'             => 'Deuda Total',
    ],
    'payments' => [
        'id'                => 'ID',
        'amount'            => 'Monto',
        'box'               => 'Caja',
        'comment'           => 'Comentario',
        'customer'          => 'Cliente',
        'date'              => 'Fecha',
        'payment_method'    => 'Método de Pago'
    ],
    'refunds' => [
        'index'             => 'Listado de devoluciones',
        'create'            => 'Crear nueva venta',
        'edit'              => 'Editar venta',
        'order'             => 'Venta',
        'id'                => 'ID',
        'box_id'            => 'ID Caja',
        'customer'          => 'Cliente',
        'date'              => 'Fecha',
        'payment_method'    => 'Método de pago',
        'discount'          => 'Descuento',
        'subtotal'          => 'Subtotal',
        'total'             => 'Total',
        'user'              => 'Usuario'
    ],
    'schedules' => [
        'id'                => 'ID',
        'date'              => 'Fecha',
        'index'             => 'Listado de agendas',
        'details'           => 'Detalle agenda',
        'view-map'          => 'Ver mapa'
    ],
    'sidebar' => [
        'boxes'                 => 'Cajas',
        'boxes-orders'          => 'Cajas y Ventas',
        'brands'                => 'Marcas',
        'catalog'               => 'Catálogo',
        'categories'            => 'Categorías',
        'credits'               => 'Créditos',
        'customers-management'  => 'Gestión Clientes',
        'customers'             => 'Clientes',
        'collections'           => 'Cobros',
        'inventory'             => 'Inventario',
        'debtors'               => 'Morosos',
        'pending-to-schedule'   => 'Pendientes por agendar',
        'refunds'               => 'Devoluciones',
        'general'               => 'General',
        'orders'                => 'Ventas',
        'products'              => 'Productos',
        'products_transfers'    => 'Productos Transferencias',
        'settings'              => 'Configuración',
        'users'                 => 'Usuarios',
        'roles'                 => 'Roles',
        'schedules'             => 'Agendas',
        'permissions'           => 'Permisos',
        'privileges'            => 'Privilegios',
        'zones'                 => 'Zonas'
    ],
    'spendings' => [
        'id'      => 'ID',
        'date'    => 'Fecha',
        'amount'  => 'Monto',
        'comment' => 'Comentario',
        'picture' => 'Foto'
    ],
    'visits' => [
        'id'            => 'ID',
        'address'       => 'Dirección',
        'comment'       => 'Comentario',
        'customer'      => 'Cliente',
        'completed'     => 'Completada',
        'date'          => 'Fecha',
        'responsable'   => 'Responsable',
        'schedule'      => 'Agenda',
        'suggested_collection'      => 'Cobro sugerido',
        'planning_collection'       => 'Revisar visitas',
        'planning_collection_negative_alert'  => 'La planificación de cobranza de :customer NO CUBRE la deuda total con las visitas programadas en la agenda.
                                         Se recomienda crear una nueva visita o modificar las existentes ajustando el cobro sugerido faltante de :suggested_collection_total.',
        'planning_collection_positive_alert'  => 'La planificación de cobranza de :customer tiene un EXCEDENTE en la suma total de cobros sugeridos de las visitas programadas en la agenda.
                                         Se recomienda eliminar o modificar las visitas existentes  ajustando el cobro sugerido excedente de :suggested_collection_total.',                           
        'whatsapp'      => 'Whatsapp',
        'zone'          => 'Zona'
    ],
    'zones' => [
        'index'                 => 'Listado de Zonas',
        'create'                => 'Crear nueva zona',
        'edit'                  => 'Editar zona',
        'address_destination'   => 'Dirección destino',
        'name'                  => 'Nombre',
        'position'              => 'Posición'
    ]
];