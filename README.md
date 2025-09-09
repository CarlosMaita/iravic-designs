# Iravic Designs - Sistema E-commerce

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Laravel](https://img.shields.io/badge/Laravel-9.x-red.svg)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-2.6-green.svg)](https://vuejs.org)
[![PHP](https://img.shields.io/badge/PHP-8.0+-blue.svg)](https://php.net)

**Iravic Designs** es una plataforma de comercio electrónico completa desarrollada con Laravel 9 y Vue.js. El sistema incluye un panel de administración avanzado construido con CoreUI, gestión integral de inventarios, procesamiento de órdenes, y un frontend responsivo para clientes.

## 🌟 Descripción del Proyecto

Iravic Designs es una solución e-commerce robusta que combina la potencia del backend de Laravel con la interactividad del frontend de Vue.js. Está diseñado para manejar operaciones comerciales complejas, desde la gestión de productos hasta el procesamiento de pagos y la administración de clientes.

## 📋 Tabla de Contenidos

* [Características Principales](#-características-principales)
* [Tecnologías y Versiones](#-tecnologías-y-versiones)
* [Requisitos del Sistema](#-requisitos-del-sistema)
* [Instalación](#-instalación)
* [Configuración](#-configuración)
* [Uso](#-uso)
* [Funcionalidades](#-funcionalidades)
* [Estructura del Proyecto](#-estructura-del-proyecto)
* [API](#-api)
* [Testing](#-testing)
* [Desarrollo](#-desarrollo)
* [Contribución](#-contribución)
* [Licencia](#-licencia)
* [Soporte](#-soporte)

## ✨ Características Principales

- **Panel de Administración Completo**: Interfaz moderna basada en CoreUI 3.2.0
- **Gestión de Productos**: Catálogo completo con categorías, marcas, imágenes y variantes
- **Control de Inventario**: Seguimiento de stock, transferencias entre almacenes
- **Gestión de Clientes**: Perfiles de cliente, historial de compras, favoritos
- **Procesamiento de Órdenes**: Workflow completo desde carrito hasta entrega
- **Sistema de Pagos**: Integración con múltiples métodos de pago
- **Reportes y Analytics**: Dashboard con métricas de ventas y rendimiento
- **Control de Roles**: Sistema avanzado de permisos y roles de usuario
- **Responsive Design**: Optimizado para dispositivos móviles y desktop
- **API RESTful**: Endpoints completos para integración con aplicaciones móviles

## 🔧 Tecnologías y Versiones

### Backend
- **Laravel**: 9.x
- **PHP**: 8.0+
- **Base de Datos**: MySQL/PostgreSQL/SQLite
- **Cache**: Redis
- **Queue**: Laravel Queue Workers

### Frontend
- **Vue.js**: 2.6.14
- **CoreUI**: 3.2.0
- **Bootstrap**: 4.x
- **Chart.js**: 2.9.4
- **Axios**: 0.19.0

### Herramientas de Desarrollo
- **Laravel Mix**: 5.0.4
- **Webpack**: Bundling de assets
- **Sass**: Preprocesador CSS
- **ESLint**: Linting de JavaScript/Vue
- **PHP CS Fixer**: Formateo de código PHP

### Paquetes Principales
- **Spatie Media Library**: Gestión de archivos multimedia
- **Laravel DataTables**: Tablas dinámicas
- **DomPDF**: Generación de reportes PDF
- **Laravel UI**: Scaffolding de autenticación
- **PHPSpreadsheet**: Importación/exportación Excel

## 📋 Requisitos del Sistema

- **PHP**: 8.0 o superior
- **Composer**: 2.0+
- **Node.js**: 16+ (recomendado 20+)
- **NPM**: 8+
- **Base de Datos**: MySQL 5.7+, PostgreSQL 10+, o SQLite 3.8+
- **Extensiones PHP requeridas**:
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath
  - GD (para procesamiento de imágenes)

## 🚀 Instalación

### 1. Clonar el Repositorio

```bash
git clone https://github.com/CarlosMaita/iravic-designs.git
cd iravic-designs
```

### 2. Instalar Dependencias PHP

```bash
composer install --no-dev --prefer-dist --no-interaction
```
> ⚠️ **Nota**: Este proceso toma aproximadamente 3-4 minutos. No cancelar la operación.

### 3. Instalar Dependencias Node.js

```bash
npm install --ignore-scripts
```
> ⚠️ **Nota**: Este proceso toma aproximadamente 8-10 minutos. Se esperan advertencias de deprecación.

### 4. Configuración del Entorno

```bash
# Copiar archivo de configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

## ⚙️ Configuración

### Configuración de Base de Datos

#### Para SQLite (Recomendado para desarrollo)

```bash
# Crear archivo de base de datos
touch database/database.sqlite
```

Editar el archivo `.env`:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/ruta/absoluta/al/proyecto/database/database.sqlite
```

#### Para MySQL

Editar el archivo `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=iravic_designs
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

#### Para PostgreSQL

```bash
# Crear usuario PostgreSQL
sudo -u postgres createuser --interactive
# nombre: iravic_user

# Establecer contraseña
sudo -u postgres psql
postgres=# ALTER USER iravic_user WITH ENCRYPTED PASSWORD 'tu_contraseña';
postgres=# \q

# Crear base de datos
sudo -u postgres createdb iravic_designs
```

Editar el archivo `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=iravic_designs
DB_USERNAME=iravic_user
DB_PASSWORD=tu_contraseña
```

### Migrar Base de Datos

```bash
# Ejecutar migraciones y seeders
php artisan migrate:refresh --seed
```

> ⚠️ **Limitaciones Conocidas**: Algunas migraciones pueden fallar con SQLite debido a sintaxis específica de MySQL (ENUM, MODIFY COLUMN). El sistema funcionará con esquema básico.

### Configuración de Assets

> ⚠️ **Problema Conocido**: La compilación de assets falla actualmente debido a incompatibilidad de node-sass con Node.js 20+.

```bash
# Intentar compilar assets (puede fallar)
npm run dev
```

**Solución temporal**: Usar assets precompilados disponibles en el directorio `public/`.

## 🎯 Uso

### Iniciar el Servidor

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

### Acceso al Sistema

- **URL**: [http://localhost:8000](http://localhost:8000)
- **Panel Admin**: [http://localhost:8000/login](http://localhost:8000/login)

### Credenciales por Defecto

- **Email**: admin@admin.com
- **Contraseña**: password
- **Roles**: usuario y administrador

### Ejecutar Tests

```bash
# Instalar dependencias de desarrollo
composer install

# Ejecutar suite de tests
vendor/bin/phpunit
```

## 🛍️ Funcionalidades

### Módulo de Administración

#### 🏪 Gestión de Productos
- **Catálogo de Productos**: CRUD completo de productos
- **Categorías y Subcategorías**: Organización jerárquica
- **Marcas**: Gestión de marcas y fabricantes
- **Variantes de Producto**: Colores, tallas, etc.
- **Galería de Imágenes**: Múltiples imágenes por producto
- **SEO**: URLs amigables y metadatos

#### 📦 Control de Inventario
- **Gestión de Stock**: Seguimiento en tiempo real
- **Múltiples Almacenes**: Gestión multi-ubicación
- **Transferencias**: Movimiento entre almacenes
- **Alertas de Stock**: Notificaciones de stock bajo
- **Importación/Exportación**: Excel para inventarios masivos

#### 👥 Gestión de Clientes
- **Perfiles de Cliente**: Información completa
- **Historial de Compras**: Seguimiento de órdenes
- **Lista de Favoritos**: Productos guardados
- **Segmentación**: Grupos de clientes
- **Comunicación**: Sistema de mensajería

#### 📋 Procesamiento de Órdenes
- **Workflow de Órdenes**: Desde carrito hasta entrega
- **Estados de Orden**: Seguimiento completo
- **Facturación**: Generación automática de facturas
- **Devoluciones**: Gestión de productos devueltos
- **Reportes**: Analytics de ventas

#### 💳 Sistema de Pagos
- **Múltiples Métodos**: Tarjetas, transferencias, efectivo
- **Procesamiento Seguro**: Integración con gateways
- **Conciliación**: Seguimiento de pagos
- **Reportes Financieros**: Estado de cuentas

#### 📊 Reportes y Analytics
- **Dashboard Ejecutivo**: Métricas clave
- **Reportes de Ventas**: Por período, producto, cliente
- **Analytics de Inventario**: Rotación, valorización
- **Reportes Financieros**: Ingresos, gastos, rentabilidad

### Módulo Frontend (E-commerce)

#### 🛒 Experiencia de Compra
- **Catálogo Responsive**: Navegación intuitiva
- **Búsqueda Avanzada**: Filtros múltiples
- **Carrito de Compras**: Persistente y dinámico
- **Checkout Optimizado**: Proceso simplificado
- **Cuenta de Usuario**: Perfil y historial

#### 🔐 Sistema de Roles y Permisos
- **Roles Personalizables**: Admin, Vendedor, Cliente
- **Permisos Granulares**: Control específico por módulo
- **Auditoria**: Registro de acciones de usuario
- **Seguridad**: Autenticación de dos factores


## 🏗️ Estructura del Proyecto

```
iravic-designs/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # Controladores del panel admin
│   │   │   │   ├── Catalog/        # Productos, categorías, marcas
│   │   │   │   ├── Customers/      # Gestión de clientes
│   │   │   │   ├── Orders/         # Gestión de órdenes
│   │   │   │   └── Config/         # Configuración del sistema
│   │   │   ├── Api/                # Controladores API
│   │   │   └── Ecommerce/          # Frontend público
│   │   ├── Requests/               # Form requests
│   │   └── Middleware/             # Middleware personalizado
│   ├── Models/                     # Modelos Eloquent
│   ├── Services/                   # Lógica de negocio
│   ├── Repositories/               # Acceso a datos
│   └── Helpers/                    # Funciones auxiliares
├── database/
│   ├── migrations/                 # Migraciones de base de datos
│   ├── seeders/                    # Datos iniciales
│   └── factories/                  # Factories para testing
├── resources/
│   ├── js/
│   │   ├── components/
│   │   │   ├── catalog/            # Componentes Vue de catálogo
│   │   │   ├── orders/             # Componentes Vue de órdenes
│   │   │   ├── customers/          # Componentes Vue de clientes
│   │   │   └── shared/             # Componentes reutilizables
│   │   └── app.js                  # Punto de entrada Vue
│   ├── sass/                       # Estilos SCSS
│   └── views/                      # Vistas Blade
│       ├── admin/                  # Vistas del panel admin
│       └── ecommerce/              # Vistas del frontend
├── public/                         # Assets públicos
├── routes/
│   ├── web.php                     # Rutas web
│   └── api.php                     # Rutas API
└── tests/                          # Tests automatizados
```

## 🔌 API

### Endpoints Principales

#### Autenticación
```http
POST /api/login          # Iniciar sesión
POST /api/logout         # Cerrar sesión
POST /api/register       # Registro de cliente
```

#### Productos
```http
GET    /api/products              # Listar productos
GET    /api/products/{id}         # Obtener producto específico
POST   /api/products              # Crear producto (admin)
PUT    /api/products/{id}         # Actualizar producto (admin)
DELETE /api/products/{id}         # Eliminar producto (admin)
```

#### Categorías
```http
GET    /api/categories            # Listar categorías
GET    /api/categories/{id}       # Obtener categoría específica
```

#### Carrito y Órdenes
```http
GET    /api/cart                  # Obtener carrito actual
POST   /api/cart/add              # Agregar producto al carrito
PUT    /api/cart/update           # Actualizar cantidad
DELETE /api/cart/remove           # Remover producto
POST   /api/orders                # Crear orden
GET    /api/orders                # Listar órdenes del usuario
```

### Autenticación API

La API utiliza Laravel Sanctum para autenticación:

```javascript
// Ejemplo de uso con axios
axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
```

## 🧪 Testing

### Configuración de Tests

```bash
# Copiar configuración de testing
cp .env.example .env.testing

# Configurar base de datos de testing
php artisan key:generate --env=testing
```

### Ejecutar Tests

```bash
# Tests completos
vendor/bin/phpunit

# Tests específicos
vendor/bin/phpunit --filter ProductTest

# Con coverage
vendor/bin/phpunit --coverage-html coverage/
```

### Tipos de Tests

- **Unit Tests**: Lógica de negocio y modelos
- **Feature Tests**: Endpoints y flujos completos
- **Browser Tests**: Pruebas de interfaz (Laravel Dusk)

## 👨‍💻 Desarrollo

### Comandos de Desarrollo

```bash
# Instalación inicial
composer install
npm install

# Desarrollo diario
php artisan serve                 # Servidor local
npm run watch                     # Watch assets (si funciona)

# Linting y formateo
npm run lint:js                   # Verificar JavaScript/Vue
npm run lint:js:fix               # Corregir automáticamente
composer run format:php           # Formatear código PHP

# Base de datos
php artisan migrate:refresh --seed    # Resetear BD con datos
php artisan migrate:status             # Estado de migraciones
```

### Estándares de Código

El proyecto sigue estándares específicos documentados en:
- `CODING_STANDARDS.md` - Estándares detallados
- `IMPLEMENTATION_GUIDE.md` - Guía de implementación

#### Herramientas de Calidad
- **PHP CS Fixer**: Formateo automático de PHP
- **ESLint**: Linting de JavaScript/Vue
- **StyleCI**: Integración continua de estilo

### Agregar Nuevas Funcionalidades

1. **Crear Modelo y Migración**:
```bash
php artisan make:model NuevoModelo -m
```

2. **Crear Controlador**:
```bash
php artisan make:controller Admin/NuevoController --resource
```

3. **Crear Form Request**:
```bash
php artisan make:request Admin/NuevoRequest
```

4. **Agregar Rutas**:
```php
// routes/web.php
Route::resource('admin/nuevo', NuevoController::class);
```

5. **Crear Componente Vue**:
```bash
# En resources/js/components/
touch nuevo-component.vue
```

### Variables de Entorno Importantes

```env
APP_NAME=IravicDesigns
APP_ENV=local|production
APP_DEBUG=true|false
APP_URL=http://localhost:8000

# Base de datos
DB_CONNECTION=mysql|pgsql|sqlite
DB_HOST=127.0.0.1
DB_DATABASE=iravic_designs

# Cache y sesiones
CACHE_DRIVER=file|redis
SESSION_DRIVER=file|database

# Email
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io

# Almacenamiento
FILESYSTEM_DRIVER=local|s3
```

## 🤝 Contribución

### Proceso de Contribución

1. **Fork del repositorio**
2. **Crear rama feature**:
```bash
git checkout -b feature/nueva-funcionalidad
```
3. **Seguir estándares de codificación**
4. **Ejecutar tests y linters**:
```bash
vendor/bin/phpunit
npm run lint:js:fix
composer run format:php
```
5. **Crear Pull Request**

### Guías de Contribución

- Seguir los estándares definidos en `CODING_STANDARDS.md`
- Escribir tests para nuevas funcionalidades
- Documentar cambios en código
- Usar commits descriptivos en español
- Respetar la arquitectura existente

### Reportar Issues

Al reportar problemas, incluir:
- Versión de PHP y Node.js
- Pasos para reproducir
- Logs de error
- Capturas de pantalla (si aplica)

## 📄 Licencia

Este proyecto está licenciado bajo la [Licencia MIT](LICENSE).

```
MIT License

Copyright (c) 2025 Iravic Designs

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

## 📞 Soporte

### Documentación Adicional
- [Estándares de Codificación](CODING_STANDARDS.md)
- [Guía de Implementación](IMPLEMENTATION_GUIDE.md)
- [Documentación de Laravel](https://laravel.com/docs)
- [Documentación de Vue.js](https://vuejs.org/v2/guide/)
- [Documentación de CoreUI](https://coreui.io/docs/)

### Contacto

Para soporte, dudas o sugerencias:
- **Email**: soporte@iravicdesigns.com
- **Issues**: [GitHub Issues](https://github.com/CarlosMaita/iravic-designs/issues)
- **Contribuciones**: [GitHub Pull Requests](https://github.com/CarlosMaita/iravic-designs/pulls)

### Community y Recursos

- [Laravel España](https://laraveles.com/)
- [Vue.js Madrid](https://www.meetup.com/VueJS-Madrid/)
- [PHP España](https://php.es/)

## 🔄 Changelog

### Versión Actual: 1.0.5

#### Funcionalidades Principales
- ✅ Sistema completo de e-commerce
- ✅ Panel de administración avanzado
- ✅ Gestión de inventarios multi-almacén
- ✅ API RESTful completa
- ✅ Sistema de roles y permisos
- ✅ Reportes y analytics

#### Próximas Mejoras
- 🔄 Actualización a Laravel 10
- 🔄 Migración a Vue.js 3
- 🔄 Integración con PWA
- 🔄 Sistema de notificaciones push
- 🔄 Integración con APIs de envío

---

**Desarrollado con ❤️ por el equipo de Iravic Designs**

