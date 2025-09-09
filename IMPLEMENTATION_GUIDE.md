# Implementación de Estándares de Codificación

Este documento explica cómo implementar y utilizar los estándares de codificación definidos para el proyecto Iravic Designs.

## 📋 Archivos Creados

### 1. Documentación Principal
- `CODING_STANDARDS.md` - Estándares completos de codificación

### 2. Configuración de Herramientas
- `.php-cs-fixer.php` - Configuración para PHP CS Fixer
- `.eslintrc.js` - Configuración para ESLint (Vue.js)
- `package.json` - Scripts de linting actualizados

### 3. Ejemplos de Implementación
- `app/Services/ProductService.php` - Servicio ejemplo que sigue los estándares
- `app/Http/Controllers/admin/Examples/ExampleProductController.php` - Controlador ejemplo
- `resources/js/components/shared/ModalProductFormComponent.vue` - Componente Vue ejemplo

## 🚀 Instalación de Herramientas

### 1. Instalar PHP CS Fixer

```bash
composer require --dev friendsofphp/php-cs-fixer
```

### 2. Instalar ESLint y plugins para Vue

```bash
npm install --save-dev eslint eslint-plugin-vue
```

## 📝 Comandos de Desarrollo

### Formateo de Código PHP

```bash
# Revisar archivos sin modificar
vendor/bin/php-cs-fixer fix --dry-run --diff

# Aplicar formateo automáticamente
vendor/bin/php-cs-fixer fix

# Agregar script al composer.json
composer exec php-cs-fixer fix
```

### Linting de JavaScript/Vue

```bash
# Verificar errores de linting
npm run lint:js

# Corregir errores automáticamente
npm run lint:js:fix
```

## 🏗️ Estructura de Directorios Recomendada

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── Catalog/          # Productos, categorías, marcas
│   │   │   ├── Customers/        # Gestión de clientes
│   │   │   ├── Orders/           # Gestión de órdenes
│   │   │   └── Examples/         # Ejemplos de implementación
│   │   ├── Api/                  # Controladores API
│   │   └── Ecommerce/            # Frontend público
│   ├── Requests/
│   │   └── Admin/
│   │       ├── Catalog/
│   │       ├── Customers/
│   │       └── Orders/
│   └── Middleware/
├── Models/
├── Services/                     # Lógica de negocio compleja
├── Repositories/                 # Acceso a datos (opcional)
└── Helpers/

resources/
|
├── templates/
│   └── cartzilla/                # Plantillas y recursos de Cartzilla
├── js/
│   ├── components/
│   │   ├── catalog/              # Componentes de catálogo
│   │   ├── orders/               # Componentes de órdenes
│   │   ├── customers/            # Componentes de clientes
│   │   └── shared/               # Componentes reutilizables
│   ├── mixins/                   # Mixins de Vue
│   ├── utils/                    # Utilidades JavaScript
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

## 🎯 Ejemplos de Uso

### 1. Crear un Nuevo Controlador

```php
<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:manage-categories');
    }

    public function store(CategoryRequest $request)
    {
        try {
            $category = Category::create($request->validated());

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoría creada correctamente.',
                    'data' => $category
                ]);
            }

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Categoría creada correctamente.');
                
        } catch (\Exception $e) {
            \Log::error('Error creating category: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la categoría.'
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Error al crear la categoría.');
        }
    }
}
```

### 2. Crear un Form Request

```php
<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('manage-categories');
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|min:2|max:100',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ];

        if ($this->isMethod('POST')) {
            $rules['slug'] = 'required|unique:categories,slug';
        } else {
            $rules['slug'] = [
                'required',
                Rule::unique('categories', 'slug')->ignore($this->route('category')->id)
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'name.min' => 'El nombre debe tener al menos :min caracteres.',
            'slug.required' => 'El slug es obligatorio.',
            'slug.unique' => 'Ya existe una categoría con este slug.'
        ];
    }
}
```

### 3. Crear un Componente Vue

```vue
<template>
    <div class="category-selector">
        <label for="categorySelect" class="form-label">
            Categoría <span v-if="required" class="text-danger">*</span>
        </label>
        <select
            v-model="selectedCategory"
            class="form-select"
            id="categorySelect"
            :class="{ 'is-invalid': hasError }"
            :required="required"
            @change="handleChange"
        >
            <option value="">{{ placeholder }}</option>
            <option
                v-for="category in categories"
                :key="category.id"
                :value="category.id"
            >
                {{ category.name }}
            </option>
        </select>
        <div v-if="error" class="invalid-feedback">
            {{ error }}
        </div>
    </div>
</template>

<script>
export default {
    name: 'CategorySelectorComponent',
    
    props: {
        value: {
            type: [String, Number],
            default: ''
        },
        categories: {
            type: Array,
            default: () => []
        },
        placeholder: {
            type: String,
            default: 'Seleccionar categoría...'
        },
        required: {
            type: Boolean,
            default: false
        },
        error: {
            type: String,
            default: ''
        }
    },
    
    computed: {
        selectedCategory: {
            get() {
                return this.value;
            },
            set(value) {
                this.$emit('input', value);
            }
        },
        
        hasError() {
            return !!this.error;
        }
    },
    
    methods: {
        handleChange() {
            this.$emit('change', this.selectedCategory);
        }
    }
};
</script>
```

## 📋 Lista de Verificación para Nuevas Funcionalidades

### Backend (Laravel)

- [ ] ¿El controlador usa Form Requests para validación?
- [ ] ¿Se maneja correctamente la autorización con middleware o gates?
- [ ] ¿La lógica compleja está en un Service o Repository?
- [ ] ¿Se manejan las excepciones apropiadamente?
- [ ] ¿Las respuestas AJAX siguen el formato estándar?
- [ ] ¿Se registran los eventos importantes en logs?

### Frontend (Vue.js)

- [ ] ¿El componente tiene un nombre descriptivo en PascalCase?
- [ ] ¿Las props están correctamente tipadas?
- [ ] ¿Se emiten eventos para comunicación con el padre?
- [ ] ¿El componente maneja estados de loading y error?
- [ ] ¿Se siguen las convenciones de nomenclatura?
- [ ] ¿El componente es reutilizable?

### UI/UX (Cartzilla)

- [ ] ¿Se utilizan las clases CSS de Cartzilla?
- [ ] ¿Los iconos son de la librería de Cartzilla?
- [ ] ¿Los componentes siguen el diseño de la plantilla?
- [ ] ¿Es responsive y accesible?
- [ ] ¿Los formularios usan validation de Bootstrap?

## 🔧 Configuración del IDE

### VS Code

Crear `.vscode/settings.json`:

```json
{
    "php-cs-fixer.executablePath": "./vendor/bin/php-cs-fixer",
    "php-cs-fixer.onsave": true,
    "eslint.autoFixOnSave": true,
    "vetur.validation.template": false,
    "vetur.validation.script": true,
    "vetur.validation.style": true,
    "emmet.includeLanguages": {
        "vue-html": "html",
        "vue": "html"
    }
}
```

### PHPStorm

1. Ir a `File > Settings > Tools > External Tools`
2. Agregar PHP CS Fixer como herramienta externa
3. Configurar ESLint en `File > Settings > Languages & Frameworks > JavaScript > Code Quality Tools > ESLint`

## 📚 Recursos Adicionales

- [Laravel Best Practices](https://github.com/alexeymezenin/laravel-best-practices)
- [Vue.js Style Guide](https://vuejs.org/v2/style-guide/)
- [PHP CS Fixer Documentation](https://cs.symfony.com/)
- [ESLint Vue Plugin](https://eslint.vuejs.org/)
- [Cartzilla Documentation](https://cartzilla.createx.studio/docs/installation.html)

## 🤝 Contribución

Para contribuir al proyecto:

1. Fork el repositorio
2. Crear una rama feature (`git checkout -b feature/nueva-funcionalidad`)
3. Seguir los estándares de codificación definidos
4. Ejecutar linters antes del commit
5. Crear un pull request

## 📞 Soporte

Para dudas sobre los estándares de codificación, contactar al equipo de desarrollo o crear un issue en el repositorio.