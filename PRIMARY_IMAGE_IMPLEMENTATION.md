# Funcionalidad de Imagen Principal de Producto

## Resumen
Esta funcionalidad permite a los administradores seleccionar una imagen principal para cada producto (regular o con combinaciones). La imagen principal se mostrará en las tarjetas de producto del catálogo e-commerce y será la primera imagen en la galería del detalle de producto.

## Cambios Implementados

### 1. Base de Datos
**Archivo**: `database/migrations/2025_12_16_025251_add_is_primary_to_products_images_table.php`

Se agregó la columna `is_primary` (boolean) a la tabla `products_images`:
```php
$table->boolean('is_primary')->default(false)->after('combination_index');
```

### 2. Modelo ProductImage
**Archivo**: `app/Models/ProductImage.php`

- Agregado `is_primary` al array `$fillable`
- Implementado observer en método `boot()` que garantiza que solo una imagen por producto pueda ser principal
- Cuando se marca una imagen como principal, automáticamente desmarca las demás del mismo producto

### 3. Helpers y Resources
**Archivos**: 
- `app/Helpers/ProductEcommerceHelper.php`
- `app/Http/Resources/ProductResource.php`

Modificados los métodos:
- `getUrlThumbnailProduct()`: Prioriza imagen principal sobre la primera
- `getUrlThumbnailCombination()`: Prioriza imagen principal por combinación
- `getImagesProduct()`: Ordena imágenes con `sortByDesc('is_primary')`
- `getImagesCombination()`: Ordena imágenes con `sortByDesc('is_primary')`

### 4. Controlador Admin
**Archivo**: `app/Http/Controllers/admin/catalog/ProductImageController.php`

Nuevo método `setPrimary()`:
- Endpoint: `POST /admin/catalogo/producto-imagen/set-primary`
- Parámetro: `image_id`
- Respuesta: JSON con success/error

Modificado método `index()`:
- Agregada columna `is_primary` al DataTable
- Badge verde "Principal" para imagen actual
- Botón estrella para establecer como principal

### 5. Rutas
**Archivo**: `routes/web.php`

```php
Route::post('producto-imagen/set-primary', 'ProductImageController@setPrimary')
    ->name('producto-imagen.set-primary');
```

### 6. Vistas Admin
**Archivos**:
- `resources/views/dashboard/catalog/products/show.blade.php`
- `resources/views/dashboard/catalog/products/js/show.blade.php`

Cambios en la tabla de imágenes:
- Agregadas columnas "Principal" y "Acciones"
- Handler AJAX para botón "establecer como principal"
- Recarga automática del DataTable después de establecer

### 7. Componente Vue (Formulario de Producto)
**Archivo**: `resources/js/components/catalog/ProductFormComponent.vue`

Nuevo método `setPrimaryImage()`:
- Realiza petición AJAX para marcar imagen como principal
- Actualiza estado local de imágenes
- Muestra notificación de éxito/error

Nuevos estilos CSS:
- `.btn-img-primary`: Botón estrella amarillo (no principal)
- `.badge-primary-img`: Badge estrella verde (principal actual)

Template actualizado:
- Estrella verde (`fa-star`) cuando es imagen principal
- Estrella amarilla (`far fa-star`) para establecer como principal
- Icono de eliminar mantiene su funcionalidad

## Uso

### Para Administradores

#### Productos Regulares
1. Ir a editar producto
2. En la pestaña "Multimedia", subir imágenes
3. Ver tabla de imágenes con columnas: Foto | Principal | Acciones
4. Hacer clic en botón estrella (⭐) de la imagen deseada
5. La imagen seleccionada mostrará badge "Principal" en verde
6. Las demás imágenes mostrarán botón estrella para seleccionarlas

#### Productos con Combinaciones
1. Ir a editar producto
2. En la pestaña "Combinaciones", expandir una combinación
3. Subir imágenes para la combinación
4. Ver miniaturas de imágenes con iconos:
   - ⭐ (verde): Imagen principal actual
   - ☆ (amarillo): Hacer clic para establecer como principal
   - ✕ (rojo): Eliminar imagen
5. Hacer clic en estrella amarilla para cambiar imagen principal

### Para E-commerce (Automático)

#### Tarjetas de Producto (Catálogo)
- Muestra la imagen marcada como principal
- Si no hay imagen principal, muestra la primera imagen
- Aplica tanto a productos regulares como combinaciones

#### Detalle de Producto
- La imagen principal aparece primero en la galería
- Las demás imágenes siguen ordenadas
- Si no hay imagen principal, muestra todas en orden de carga

## Comportamiento

### Reglas de Negocio
1. Solo una imagen por producto puede ser principal
2. Al establecer una imagen como principal, las demás se desmarcan automáticamente
3. Si se elimina la imagen principal, ninguna otra se marca automáticamente
4. Productos sin imagen principal usan la primera imagen como fallback
5. Cada combinación puede tener su propia imagen principal

### Compatibilidad
- ✅ Productos regulares (is_regular = 1)
- ✅ Productos con combinaciones (is_regular = 0)
- ✅ Múltiples colores y tallas
- ✅ SQLite y MySQL
- ✅ APIs existentes (ProductResource)

## Migraciones

### Ejecutar Migraciones
```bash
php artisan migrate
```

### Rollback (si necesario)
```bash
php artisan migrate:rollback --step=1
```

Esto eliminará la columna `is_primary` de la tabla `products_images`.

## Testing

### Tests Unitarios
**Archivo**: `tests/Unit/ProductImagePrimaryTest.php`

Ejecutar tests:
```bash
vendor/bin/phpunit --filter=ProductImagePrimaryTest
```

Tests incluidos:
- ✅ `is_primary` está en fillable
- ✅ ProductImage puede crearse con `is_primary`
- ✅ `is_primary` por defecto es false
- ✅ ProductImage puede instanciarse

### Pruebas Manuales Recomendadas

1. **Crear producto regular con imágenes**
   - Subir 3 imágenes
   - Establecer segunda imagen como principal
   - Verificar en e-commerce que se muestra la segunda imagen

2. **Crear producto con combinaciones**
   - Crear 2 combinaciones (ej: Rojo, Azul)
   - Subir 2 imágenes a cada combinación
   - Establecer imagen principal diferente para cada combinación
   - Verificar que cada combinación muestra su imagen principal

3. **Eliminar imagen principal**
   - Eliminar la imagen marcada como principal
   - Verificar que producto sigue mostrando primera imagen restante

4. **API de productos**
   - Hacer GET a `/api/products`
   - Verificar que `url_thumbnail` apunta a imagen principal
   - Verificar que array `images` tiene imagen principal primero

## Solución de Problemas

### La imagen principal no se muestra en e-commerce
- Verificar que la migración se ejecutó correctamente
- Verificar que el navegador no tiene caché de las imágenes
- Revisar que ProductEcommerceHelper y ProductResource tienen los cambios

### El botón estrella no funciona
- Verificar que la ruta `producto-imagen.set-primary` existe
- Revisar consola del navegador para errores JavaScript
- Verificar que el token CSRF está presente en la página

### Solo funciona en productos regulares
- Verificar que las imágenes de combinaciones tienen `combination_index` correcto
- La lógica funciona igual para regulares y combinaciones

## Archivos Modificados

### Backend
- ✅ `app/Models/ProductImage.php`
- ✅ `app/Helpers/ProductEcommerceHelper.php`
- ✅ `app/Http/Resources/ProductResource.php`
- ✅ `app/Http/Controllers/admin/catalog/ProductImageController.php`
- ✅ `routes/web.php`
- ✅ `database/migrations/2025_12_16_025251_add_is_primary_to_products_images_table.php`

### Frontend
- ✅ `resources/views/dashboard/catalog/products/show.blade.php`
- ✅ `resources/views/dashboard/catalog/products/js/show.blade.php`
- ✅ `resources/js/components/catalog/ProductFormComponent.vue`

### Tests
- ✅ `tests/Unit/ProductImagePrimaryTest.php`

### Fixes Adicionales (Compatibilidad SQLite)
- ✅ `database/migrations/2025_06_20_142239_remove_prices_on_products_table.php`
- ✅ `database/migrations/2025_09_10_135818_remove_unused_customer_fields_and_add_shipping_info.php`

## Conclusión

Esta funcionalidad cumple con todos los requisitos especificados en el issue:
- ✅ Los administradores pueden seleccionar la imagen principal
- ✅ La selección es mediante iconos intuitivos (estrellas)
- ✅ La imagen principal se muestra en las tarjetas de producto
- ✅ La imagen principal es la primera en el detalle de producto
- ✅ Funciona tanto para productos regulares como con combinaciones

El código es robusto, mantiene la lógica de fallback (primera imagen si no hay principal), y se integra perfectamente con el sistema existente sin romper funcionalidad previa.
