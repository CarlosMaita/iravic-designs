# Estándares de Codificación - Iravic Designs

Este documento define los estándares de codificación para el proyecto Iravic Designs, un sistema e-commerce desarrollado con Laravel 9 y Vue.js, utilizando la plantilla Cartzilla.

## Tabla de Contenidos

1. [Estándares Generales](#estándares-generales)
2. [Backend - Laravel](#backend---laravel)
3. [Frontend - Vue.js](#frontend---vuejs)
4. [UI/UX - Plantilla Cartzilla](#uiux---plantilla-cartzilla)
5. [Estructura de Archivos](#estructura-de-archivos)
6. [Herramientas y Linting](#herramientas-y-linting)

## Estándares Generales

### Principios Fundamentales

- **Código limpio y legible**: Priorizar la claridad sobre la complejidad
- **Consistencia**: Mantener patrones consistentes en todo el proyecto
- **Reutilización**: Crear componentes y funciones reutilizables
- **Documentación**: Comentar código complejo y mantener documentación actualizada
- **Testing**: Escribir tests para funcionalidades críticas

### Convenciones de Nomenclatura

- **Archivos**: `kebab-case` para archivos y directorios
- **Variables PHP**: `snake_case`
- **Variables JavaScript**: `camelCase`
- **Constantes**: `UPPER_SNAKE_CASE`
- **Clases**: `PascalCase`
- **Métodos**: `camelCase`

## Backend - Laravel

### 1. Arquitectura MVC

#### Controladores

Los controladores deben seguir el patrón MVC y ser responsables únicamente de:
- Recibir requests
- Validar datos (usando Form Requests)
- Delegar lógica de negocio a Servicios o Repositorios
- Retornar respuestas

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function store(ProductRequest $request)
    {
        $product = $this->productService->create($request->validated());
        
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto creado correctamente.');
    }
}
```

#### Modelos

Los modelos deben:
- Extender de `Illuminate\Database\Eloquent\Model`
- Definir `$fillable` o `$guarded` apropiadamente
- Incluir relaciones, accessors y mutators
- Mantener lógica de negocio simple

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'slug',
        'brand_id',
        'category_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean'
    ];

    // Relaciones
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }
}
```

### 2. Validación con Form Requests

**OBLIGATORIO**: Utilizar Form Requests para todas las validaciones en lugar de validar directamente en el controlador.

```php
<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('manage-products');
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|min:3|max:155',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
        ];

        if ($this->isMethod('POST')) {
            $rules['slug'] = 'required|unique:products,slug';
        } else {
            $rules['slug'] = [
                'required',
                Rule::unique('products', 'slug')->ignore($this->route('product')->id)
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.min' => 'El nombre debe tener al menos :min caracteres.',
            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un número válido.',
            'brand_id.required' => 'Debe seleccionar una marca.',
            'brand_id.exists' => 'La marca seleccionada no es válida.',
        ];
    }
}
```

### 3. Servicios y Repositorios

#### Servicios (Recomendado para lógica compleja)

```php
<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function create(array $data)
    {
        // Lógica de negocio
        $data['slug'] = $this->generateSlug($data['name']);
        
        return $this->productRepository->create($data);
    }

    protected function generateSlug(string $name): string
    {
        // Lógica para generar slug único
        return str_slug($name);
    }
}
```

### 4. Rutas

#### Separación de Responsabilidades en Rutas

**REGLA OBLIGATORIA**: Los archivos de rutas deben contener únicamente definiciones de rutas. Toda lógica de negocio debe estar en controladores.

❌ **Incorrecto** - Lógica en archivo de rutas:
```php
Route::get('debug-auth', function () {
    $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'Not authenticated']);
    }
    
    return response()->json([
        'user' => $user->name,
        'roles' => $user->roles->pluck('name'),
        // ... más lógica
    ]);
});
```

✅ **Correcto** - Delegar a controlador:
```php
Route::get('debug-auth', 'DebugController@authDebug');
```

#### Organización de Rutas

Organizar rutas en grupos lógicos con prefijos y namespaces:

```php
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    // Rutas de catálogo
    Route::group(['prefix' => 'catalogo', 'namespace' => 'Catalog'], function () {
        Route::resource('productos', 'ProductController');
        Route::resource('marcas', 'BrandController');
        Route::resource('categorias', 'CategoryController');
    });
    
    // Rutas de gestión de clientes
    Route::group(['prefix' => 'gestion-clientes', 'namespace' => 'Customers'], function () {
        Route::resource('clientes', 'CustomerController');
    });
    
    // Rutas de depuración (solo para desarrollo)
    Route::get('debug-auth', 'DebugController@authDebug');
});

// API Routes - usar controladores específicos
Route::group(['prefix' => 'api'], function () {
    Route::get('/customer/auth-check', [\App\Http\Controllers\Api\CustomerAuthController::class, 'authCheck']);
});
```

#### Beneficios de esta Práctica

- **Mantenibilidad**: Código organizado y fácil de localizar
- **Testabilidad**: Los controladores pueden ser testeados unitariamente
- **Reutilización**: La lógica en controladores puede ser reutilizada
- **Debugging**: Mejor stack traces y debugging
- **Responsabilidad única**: Cada archivo tiene una responsabilidad clara

### 5. Migraciones

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('slug')->unique();
            $table->foreignId('brand_id')->constrained();
            $table->foreignId('category_id')->constrained();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['brand_id', 'category_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
```

## Frontend - Vue.js

### 1. Componentes Vue

#### Estructura de Componentes

Crear componentes cuando se requiera **interactividad**. Los componentes deben ser:
- Reutilizables
- Responsabilidad única
- Bien documentados

```vue
<template>
    <div class="product-form">
        <!-- Formulario del producto -->
        <form @submit.prevent="submitForm">
            <div class="form-group">
                <label for="name">Nombre del Producto</label>
                <input 
                    v-model="product.name"
                    type="text" 
                    class="form-control"
                    id="name"
                    :class="{ 'is-invalid': errors.name }"
                    @input="clearError('name')"
                >
                <div v-if="errors.name" class="invalid-feedback">
                    {{ errors.name }}
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary" :disabled="loading">
                <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                {{ loading ? 'Guardando...' : 'Guardar Producto' }}
            </button>
        </form>
    </div>
</template>

<script>
export default {
    name: 'ProductFormComponent',
    
    props: {
        initialProduct: {
            type: Object,
            default: () => ({})
        },
        apiEndpoint: {
            type: String,
            required: true
        }
    },
    
    data() {
        return {
            product: {
                name: '',
                description: '',
                price: 0,
                ...this.initialProduct
            },
            errors: {},
            loading: false
        }
    },
    
    methods: {
        async submitForm() {
            this.loading = true;
            this.errors = {};
            
            try {
                const response = await this.$axios.post(this.apiEndpoint, this.product);
                this.$emit('product-saved', response.data);
                this.showSuccessMessage('Producto guardado correctamente');
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    this.showErrorMessage('Error al guardar el producto');
                }
            } finally {
                this.loading = false;
            }
        },
        
        clearError(field) {
            if (this.errors[field]) {
                this.$delete(this.errors, field);
            }
        },
        
        showSuccessMessage(message) {
            // Implementar notificación de éxito
            alert(message); // Reemplazar con toast/notification
        },
        
        showErrorMessage(message) {
            // Implementar notificación de error
            alert(message); // Reemplazar con toast/notification
        }
    }
}
</script>

<style scoped>
.product-form {
    max-width: 600px;
}

.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}
</style>
```

#### Registro de Componentes

Registrar componentes globalmente en `resources/js/app.js`:

```javascript
// Componentes de catálogo
Vue.component('product-form', require('./components/catalog/ProductFormComponent.vue').default);
Vue.component('product-list', require('./components/catalog/ProductListComponent.vue').default);

// Componentes de órdenes
Vue.component('order-form', require('./components/orders/OrderFormComponent.vue').default);
Vue.component('modal-discount', require('./components/orders/ModalDiscount.vue').default);
```

### 2. Convenciones de Nomenclatura

- **Archivos**: `PascalCase` terminados en `Component.vue`
- **Nombres de componentes**: `kebab-case` en templates, `PascalCase` en JavaScript
- **Props**: `camelCase`
- **Events**: `kebab-case`

### 3. Comunicación entre Componentes

#### Props y Events

```vue
<!-- Componente Padre -->
<template>
    <product-form 
        :initial-product="selectedProduct"
        :api-endpoint="'/api/products'"
        @product-saved="handleProductSaved"
        @product-updated="handleProductUpdated"
    />
</template>

<script>
export default {
    data() {
        return {
            selectedProduct: {}
        }
    },
    
    methods: {
        handleProductSaved(product) {
            this.products.push(product);
        },
        
        handleProductUpdated(product) {
            const index = this.products.findIndex(p => p.id === product.id);
            if (index !== -1) {
                this.$set(this.products, index, product);
            }
        }
    }
}
</script>
```

## UI/UX - Plantilla Cartzilla

### 1. Utilización de la Plantilla

La plantilla Cartzilla se encuentra en `public/assets/cartzilla/` y debe ser la **referencia principal** para todos los componentes UI.

#### Estructura Base

```blade
{{-- resources/views/layouts/cartzilla.blade.php --}}
<!DOCTYPE html>
<html lang="es" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    
    <!-- Cartzilla Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/cartzilla/css/theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/cartzilla/icons/cartzilla-icons.min.css') }}">
    
    @stack('css')
</head>
<body>
    @yield('content')
    
    <!-- Cartzilla Core JS -->
    <script src="{{ asset('assets/cartzilla/js/theme.min.js') }}"></script>
    @stack('js')
</body>
</html>
```

### 2. Componentes UI Disponibles

#### Botones

```html
<!-- Botón primario -->
<button type="button" class="btn btn-primary">
    <i class="ci-cart"></i> Agregar al Carrito
</button>

<!-- Botón con loading -->
<button type="button" class="btn btn-primary" disabled>
    <span class="spinner-border spinner-border-sm me-2"></span>
    Procesando...
</button>
```

#### Modales

```html
<!-- Modal básico -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Título del Modal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Contenido del modal -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
```

#### Cards de Productos

```html
<div class="card product-card">
    <div class="card-img-top">
        <img src="product-image.jpg" alt="Producto">
        <div class="card-img-overlay-top">
            <span class="badge bg-success">Nuevo</span>
        </div>
    </div>
    <div class="card-body">
        <h6 class="card-title">
            <a href="#" class="text-decoration-none">Nombre del Producto</a>
        </h6>
        <div class="d-flex align-items-center justify-content-between">
            <span class="text-primary fs-5 fw-medium">$99.99</span>
            <button class="btn btn-outline-primary btn-sm">
                <i class="ci-cart"></i>
            </button>
        </div>
    </div>
</div>
```

#### Formularios

```html
<form class="needs-validation" novalidate>
    <div class="mb-3">
        <label for="productName" class="form-label">Nombre del Producto</label>
        <input type="text" class="form-control" id="productName" required>
        <div class="invalid-feedback">
            Por favor ingrese un nombre válido.
        </div>
    </div>
    
    <div class="mb-3">
        <label for="productCategory" class="form-label">Categoría</label>
        <select class="form-select" id="productCategory" required>
            <option value="">Seleccionar categoría...</option>
            <option value="1">Ropa</option>
            <option value="2">Calzado</option>
        </select>
    </div>
    
    <button type="submit" class="btn btn-primary">Guardar</button>
</form>
```

### 3. Iconografía

Utilizar los iconos de Cartzilla incluidos:

```html
<!-- Iconos comunes -->
<i class="ci-cart"></i>          <!-- Carrito -->
<i class="ci-heart"></i>         <!-- Favoritos -->
<i class="ci-user"></i>          <!-- Usuario -->
<i class="ci-search"></i>        <!-- Búsqueda -->
<i class="ci-settings"></i>      <!-- Configuración -->
<i class="ci-edit"></i>          <!-- Editar -->
<i class="ci-trash"></i>         <!-- Eliminar -->
<i class="ci-eye"></i>           <!-- Ver -->
<i class="ci-plus"></i>          <!-- Agregar -->
```

### 4. Sliders y Carruseles

```html
<!-- Slider de productos -->
<div class="swiper-container" data-swiper-options='{
    "slidesPerView": 1,
    "spaceBetween": 16,
    "pagination": {
        "el": ".swiper-pagination",
        "clickable": true
    },
    "breakpoints": {
        "768": {"slidesPerView": 2},
        "992": {"slidesPerView": 3},
        "1200": {"slidesPerView": 4}
    }
}'>
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <!-- Contenido del slide -->
        </div>
    </div>
    <div class="swiper-pagination"></div>
</div>
```

## Estructura de Archivos

### Backend

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── Catalog/
│   │   │   ├── Customers/
│   │   │   └── Orders/
│   │   ├── Api/
│   │   └── Ecommerce/
│   ├── Requests/
│   │   └── Admin/
│   │       ├── Catalog/
│   │       └── Customers/
│   └── Middleware/
├── Models/
├── Services/
├── Repositories/
└── Helpers/
```

### Frontend

```
resources/
├── js/
│   ├── components/
│   │   ├── catalog/
│   │   ├── orders/
│   │   ├── customers/
│   │   └── shared/
│   ├── mixins/
│   ├── utils/
│   └── app.js
├── sass/
│   ├── components/
│   ├── pages/
│   └── app.scss
└── views/
    ├── admin/
    ├── ecommerce/
    └── layouts/
```

## Herramientas y Linting

### PHP CS Fixer

Crear `.php-cs-fixer.php`:

```php
<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(['bootstrap', 'storage', 'vendor'])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
        'not_operator_with_successor_space' => true,
        'trailing_comma_in_multiline' => true,
        'phpdoc_scalar' => true,
        'unary_operator_spaces' => true,
        'binary_operator_spaces' => true,
        'blank_line_before_statement' => [
            'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try'],
        ],
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_var_without_name' => true,
    ])
    ->setFinder($finder);
```

### ESLint para Vue.js

Crear `.eslintrc.js`:

```javascript
module.exports = {
    env: {
        browser: true,
        es6: true,
    },
    extends: [
        'eslint:recommended',
        'plugin:vue/essential'
    ],
    globals: {
        Vue: 'readonly',
        axios: 'readonly',
        $: 'readonly'
    },
    parserOptions: {
        ecmaVersion: 2018,
        sourceType: 'module',
    },
    plugins: [
        'vue',
    ],
    rules: {
        'indent': ['error', 4],
        'linebreak-style': ['error', 'unix'],
        'quotes': ['error', 'single'],
        'semi': ['error', 'always'],
        'vue/html-indent': ['error', 4],
        'vue/script-indent': ['error', 4],
    },
};
```

### Scripts de Package.json

```json
{
    "scripts": {
        "dev": "npm run development",
        "development": "cross-env NODE_ENV=development node_modules/webpack/bin/webpack.js --progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js",
        "watch": "npm run development -- --watch",
        "watch-poll": "npm run watch -- --watch-poll",
        "hot": "cross-env NODE_ENV=development node_modules/webpack-dev-server/bin/webpack-dev-server.js --inline --hot --config=node_modules/laravel-mix/setup/webpack.config.js",
        "prod": "npm run production",
        "production": "cross-env NODE_ENV=production node_modules/webpack/bin/webpack.js --no-progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js",
        "lint:js": "eslint resources/js --ext .js,.vue",
        "lint:js:fix": "eslint resources/js --ext .js,.vue --fix",
        "format:php": "php-cs-fixer fix"
    }
}
```

## Comandos de Desarrollo

```bash
# Instalación inicial
composer install
npm install

# Desarrollo
php artisan serve
npm run watch

# Linting y formateo
npm run lint:js
npm run lint:js:fix
composer run format:php

# Testing
php artisan test
```

## Conclusión

Estos estándares deben ser seguidos por todos los desarrolladores del proyecto para mantener consistencia y calidad en el código. Se recomienda revisar y actualizar estos estándares periódicamente según evolucione el proyecto.

Para dudas o sugerencias sobre estos estándares, contactar al equipo de desarrollo.