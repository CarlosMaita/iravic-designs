# Sistema de Notificaciones - Iravic Designs

## Descripción General

El sistema de notificaciones permite enviar notificaciones a clientes tanto en la web (campana de notificaciones) como por correo electrónico. También notifica a los administradores sobre eventos importantes relacionados con órdenes y pagos.

## Características Principales

### Notificaciones para Clientes

1. **Bienvenida** - Al registrarse en la plataforma
2. **Orden Creada** - Cuando se crea una nueva orden
3. **Pago Registrado** - Cuando se registra un pago (pendiente de verificación)
4. **Pago Confirmado** - Cuando el administrador verifica un pago
5. **Pedido Enviado** - Cuando la orden es enviada con número de guía
6. **Solicitud de Reseña** - Una semana después del envío

### Notificaciones para Administradores

1. **Nueva Orden** - Cuando un cliente crea una orden
2. **Pago Recibido** - Cuando un cliente registra un pago

## Configuración

### Variables de Entorno

Asegúrate de configurar las siguientes variables en tu archivo `.env`:

```env
# Configuración de correo
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_usuario
MAIL_PASSWORD=tu_contraseña
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@iravicdesigns.com
MAIL_FROM_NAME="Iravic Designs"

# Configuración de colas (para envío asíncrono de correos)
QUEUE_CONNECTION=database
```

### Base de Datos

Ejecuta las migraciones para crear las tablas necesarias:

```bash
php artisan migrate
```

Esto creará:
- Tabla `notifications` - Para almacenar notificaciones web
- Tabla `jobs` - Para la cola de trabajos de correos

### Email del Administrador

Puedes configurar el email del administrador que recibirá las notificaciones:

1. Desde la interfaz de administración (próximamente)
2. O directamente en la tabla `configs`:

```sql
INSERT INTO configs (key, value, created_at, updated_at) 
VALUES ('admin_notification_email', 'admin@iravicdesigns.com', NOW(), NOW());
```

## Uso del Sistema

### Notificaciones Automáticas

Las notificaciones se envían automáticamente cuando ocurren ciertos eventos:

- **Registro de cliente**: `CustomerRegisterController::create()`
- **Creación de orden**: `OrderController::create()`
- **Registro de pago**: `OrderController::addPayment()`
- **Verificación de pago**: `PaymentController::verify()`
- **Envío de orden**: `OrderController::update()` (cuando el estado cambia a "enviada")

### Notificaciones Programadas

La solicitud de reseña se envía automáticamente 7 días después del envío. Para que esto funcione:

1. **Configura el scheduler de Laravel**:

Agrega a tu crontab:

```bash
* * * * * cd /ruta/a/tu/proyecto && php artisan schedule:run >> /dev/null 2>&1
```

2. **O ejecuta manualmente el comando**:

```bash
php artisan notifications:send-review-requests
```

### Worker de Cola

Para procesar los correos en segundo plano:

```bash
php artisan queue:work
```

En producción, es recomendable usar Supervisor para mantener el worker activo:

```ini
[program:iravic-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /ruta/a/tu/proyecto/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/ruta/a/tu/proyecto/storage/logs/worker.log
```

## API de Notificaciones

### Obtener Notificaciones

```
GET /api/notifications
```

Respuesta:
```json
{
  "success": true,
  "notifications": [...],
  "unread_count": 5
}
```

### Obtener Contador de No Leídas

```
GET /api/notifications/unread-count
```

Respuesta:
```json
{
  "success": true,
  "count": 5
}
```

### Marcar Como Leída

```
POST /api/notifications/{id}/read
```

### Marcar Todas Como Leídas

```
POST /api/notifications/read-all
```

## Modelo de Notificación

### Tipos de Notificación

```php
Notification::TYPE_WELCOME            // 'welcome'
Notification::TYPE_ORDER_CREATED      // 'order_created'
Notification::TYPE_PAYMENT_SUBMITTED  // 'payment_submitted'
Notification::TYPE_PAYMENT_CONFIRMED  // 'payment_confirmed'
Notification::TYPE_SHIPPED            // 'shipped'
Notification::TYPE_REVIEW_REQUEST     // 'review_request'
```

### Estructura

```php
[
    'id' => 1,
    'customer_id' => 1,
    'type' => 'welcome',
    'title' => '¡Bienvenido!',
    'message' => 'Gracias por registrarte...',
    'action_url' => 'https://...',
    'is_read' => false,
    'read_at' => null,
    'created_at' => '2025-12-11 01:00:00',
    'updated_at' => '2025-12-11 01:00:00'
]
```

## Servicio de Notificaciones

El servicio `NotificationService` centraliza toda la lógica de notificaciones:

```php
use App\Services\NotificationService;

$notificationService = app(NotificationService::class);

// Enviar notificación de bienvenida
$notificationService->sendWelcomeNotification($customer);

// Enviar notificación de orden creada
$notificationService->sendOrderCreatedNotification($order);

// Enviar notificación de pago registrado
$notificationService->sendPaymentSubmittedNotification($payment);

// Enviar notificación de pago confirmado
$notificationService->sendPaymentConfirmedNotification($payment);

// Enviar notificación de envío
$notificationService->sendShippingNotification($order);

// Enviar solicitud de reseña
$notificationService->sendReviewRequestNotification($order);
```

## Plantillas de Correo

Las plantillas de correo se encuentran en:

- Cliente: `resources/views/emails/customer/`
- Administrador: `resources/views/emails/admin/`

Las plantillas usan el formato Markdown de Laravel y son fáciles de personalizar.

## Testing

Ejecuta las pruebas del sistema de notificaciones:

```bash
vendor/bin/phpunit tests/Unit/NotificationTest.php
```

## Solución de Problemas

### Los correos no se envían

1. Verifica la configuración de correo en `.env`
2. Verifica que el worker de cola esté ejecutándose: `php artisan queue:work`
3. Revisa los logs en `storage/logs/laravel.log`

### Las notificaciones no aparecen en la web

1. Verifica que el cliente esté autenticado
2. Verifica que las notificaciones se estén creando en la base de datos
3. Revisa la consola del navegador para errores de JavaScript

### El comando programado no se ejecuta

1. Verifica que el crontab esté configurado correctamente
2. Ejecuta manualmente: `php artisan schedule:run`
3. Verifica los logs del scheduler

## Mejoras Futuras

- [ ] Interfaz de administración para configurar el email del administrador
- [ ] Notificaciones push para móvil
- [ ] Sistema de preferencias de notificación por cliente
- [ ] Plantillas de correo personalizables desde el admin
- [ ] Estadísticas de notificaciones enviadas/leídas
