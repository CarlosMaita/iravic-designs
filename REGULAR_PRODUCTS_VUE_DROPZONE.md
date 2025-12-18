# Migración de Productos Regulares a Vue Dropzone

## Resumen

Se ha implementado la migración de la carga de imágenes de productos regulares desde Dropzone.js (biblioteca vanilla JavaScript) a vue2-dropzone (componente Vue.js), permitiendo carga asíncrona de imágenes similar a los productos no regulares.

## Problema Original

**Productos Regulares (is_regular = 1):**
- Usaban Dropzone.js clásico en el tab "Multimedia"
- Las imágenes se subían de forma **síncrona** junto con el formulario
- Después de la carga, las imágenes se mostraban en un DataTable
- No había feedback visual inmediato al arrastrar y soltar imágenes
- Experiencia de usuario inferior comparada con productos no regulares

**Productos No Regulares (is_regular = 0):**
- Usaban vue2-dropzone (componente Vue) para cada combinación
- Las imágenes se subían de forma **asíncrona** inmediatamente después del drag-and-drop
- Las imágenes se mostraban inmediatamente en el componente Vue
- Mejor experiencia de usuario con feedback visual instantáneo

## Cambios Implementados

### 1. Base de Datos

**Nueva Migración:** `2025_12_17_000000_add_position_to_products_images_table.php`
- Agrega campo `position` (integer, default 0) a la tabla `products_images`
- Permite ordenar las imágenes en el futuro (preparación para drag-and-drop reordering)

**Modelo ProductImage Actualizado:**
```php
public $fillable = [
    'product_id',
    'url',
    'color_id',
    'combination_index',
    'temp_code',
    'url_original',
    'is_primary',
    'position'  // NUEVO
];
```

### 2. Componente Vue (ProductFormComponent.vue)

**Cambios en el Template:**

Antes (Tab Multimedia):
```html
<div class="tab-pane fade" id="multimedia" role="tabpanel">
    <div class="dropzone" id="myDropzone"></div>
    <div v-if="product.id" class="mt-4">
        <table id="datatable_images" class="table">
            <!-- DataTable con imágenes -->
        </table>
    </div>
</div>
```

Después (Tab Multimedia):
```html
<div class="tab-pane fade" id="multimedia" role="tabpanel">
    <div class="mt-3">
        <v-dropzone 
            ref="dropzone-regular"
            id="dropzone-regular"
            :options="dropzoneOptionsRegular"
            @vdropzone-sending-multiple="sendingEventRegular"
            @vdropzone-removed-file="removedFileEventRegular"
            @vdropzone-error="errorEvent"
            @vdropzone-success-multiple="successEventRegular"
            v-once
        ></v-dropzone>
    </div>
    <div v-if="regularProductImages.length > 0" class="mt-4">
        <h5>Imágenes cargadas ({{ regularProductImages.length }})</h5>
        <div class="row">
            <div class="img-container col-md-2 mb-3" 
                 v-for="(image, index_image) in regularProductImages" 
                 :key="`imagen-regular-${image.id || index_image}`">
                <!-- Botón eliminar -->
                <span class="btn-img-remove" @click="removeImageRegular($event, image.id)">
                    <i class="fas fa-times"></i> 
                </span>
                <!-- Indicador imagen principal -->
                <span v-if="image.is_primary" class="badge-primary-img">
                    <i class="fas fa-star"></i>
                </span>
                <span v-else class="btn-img-primary" 
                      @click="setPrimaryImageRegular($event, image.id)">
                    <i class="far fa-star"></i> 
                </span>
                <!-- Indicador de posición -->
                <span class="badge-position-img">
                    {{ index_image + 1 }}
                </span>
                <img :src="image.url_img" class="img-thumbnail">
            </div>
        </div>
    </div>
</div>
```

**Nuevas Propiedades de Datos:**
```javascript
data: () => ({
    // ... propiedades existentes
    dropzoneOptionsRegular: {
        url: "",
        acceptedFiles: "image/*",
        autoProcessQueue: true,
        uploadMultiple: true,
        parallelUploads: 10,
        maxFiles: 10,
        maxFilesize: 2,
        addRemoveLinks: true,
        thumbnailWidth: 150,
        autoDiscover: false,
    },
    regularProductImages: [],
    // ...
})
```

**Nuevos Métodos:**
- `sendingEventRegular()`: Agrega `combination_index: 0` y `temp_code` al FormData
- `successEventRegular()`: Maneja la respuesta exitosa y agrega imágenes a `regularProductImages`
- `removedFileEventRegular()`: Elimina imagen del servidor cuando se remueve del dropzone
- `removeImageRegular()`: Elimina imagen existente con confirmación SweetAlert
- `setPrimaryImageRegular()`: Establece una imagen como principal

**Estilos Nuevos:**
```css
.badge-position-img {
    position: absolute;
    bottom: 5px;
    right: 5px;
    background: rgba(0, 123, 255, 0.9);
    color: white;
    padding: 4px 8px;
    border-radius: 3px;
    font-size: 12px;
    z-index: 10;
}
```

### 3. Controlador Backend (ProductImageController.php)

**Método `saveImages()` Actualizado:**
```php
private function saveImages($product = null, $request): array
{
    $files = $request->file;
    $filesname = array();

    foreach ($files as $file) {
        $url = ImageService::save($this->filedisk, $file);
        if ($url) {
            $productImage = ProductImage::create([
                'url' => $url,
                'temp_code' => $request->input('temp_code'),
                'combination_index' => intval($request->input('combination_index')),
                'url_original' => $file->getClientOriginalName(),
            ]);
            array_push($filesname, [
                'url' => $url,
                'url_img' => $productImage->url_img,  // NUEVO: retorna URL completa
                'temp_code' => $request->input('temp_code'),
                'combination_index' => intval($request->input('combination_index')),
                'url_original' => $file->getClientOriginalName()
            ]);
        }
    }
    
    return $filesname;
}
```

### 4. JavaScript del Formulario (form.blade.php)

**Cambios Principales:**
- ✅ **Eliminado:** Inicialización de Dropzone.js (`new Dropzone("#myDropzone", {...})`)
- ✅ **Eliminado:** Lógica de submit que verificaba `myDropzone.files.length`
- ✅ **Eliminado:** DataTable de imágenes y sus event handlers
- ✅ **Simplificado:** Submit del formulario - ahora solo envía FormData con AJAX

**Antes:**
```javascript
FORM_RESOURCE.on('submit', function (e) {
    e.preventDefault();
    window.removeEventListener('beforeunload', handlerBeforeUnload);
    
    if (isProductRegular() && myDropzone.files.length > 0) {
        // Producto Regular con fotos
        myDropzone.processQueue();
    } else {
        // Envío con AJAX
        $.ajax({ /* ... */ });
    }
});
```

**Después:**
```javascript
FORM_RESOURCE.on('submit', function (e) {
    e.preventDefault();
    window.removeEventListener('beforeunload', handlerBeforeUnload);
    
    var form = $('#form-products')[0];
    var formData = new FormData(form);
    
    // Enviando formulario (las imágenes ya se subieron de forma asíncrona)
    $.ajax({ /* ... */ });
});
```

## Flujo de Trabajo

### Creación de Producto Regular con Imágenes

1. Usuario abre formulario de creación de producto
2. Marca checkbox "Es producto regular"
3. Llena información básica del producto
4. Va al tab "Multimedia"
5. Arrastra y suelta imágenes en el dropzone de Vue
6. **Las imágenes se suben INMEDIATAMENTE de forma asíncrona**
7. Las imágenes aparecen en la grilla debajo del dropzone
8. Usuario puede:
   - Eliminar imágenes (ícono X)
   - Establecer imagen principal (ícono estrella)
   - Ver posición de cada imagen (badge azul)
9. Al hacer submit del formulario:
   - Se asocian las imágenes con el producto usando `temp_code`
   - Se redirige al listado de productos

### Edición de Producto Regular

1. Usuario abre formulario de edición
2. Las imágenes existentes se cargan en `regularProductImages` desde el prop `images`
3. Usuario puede agregar más imágenes arrastrando al dropzone
4. Las nuevas imágenes se suben de forma asíncrona
5. Usuario puede gestionar todas las imágenes (eliminar, establecer principal)
6. Al hacer submit, se actualiza el producto

## Ventajas de la Nueva Implementación

### ✅ Experiencia de Usuario Mejorada
- **Feedback inmediato**: Las imágenes se muestran apenas se suben
- **Consistencia**: Productos regulares y no regulares usan la misma interfaz
- **Carga asíncrona**: No hay que esperar al submit del formulario para ver las imágenes

### ✅ Arquitectura Más Limpia
- **Un solo componente**: Vue maneja todo el ciclo de vida de las imágenes
- **Código más mantenible**: Eliminado código jQuery complejo del DataTable
- **Separación de responsabilidades**: Vue para UI, Laravel para backend

### ✅ Preparado para Futuras Mejoras
- Campo `position` preparado para drag-and-drop reordering
- Fácil agregar vuedraggable en el futuro
- Estructura extensible para agregar más funcionalidades

## Mejoras Futuras (No Implementadas)

### 1. Drag-and-Drop Reordering
Para implementar reordering visual de imágenes:

1. Instalar vuedraggable:
```bash
npm install vuedraggable
```

2. Importar en ProductFormComponent.vue:
```javascript
import draggable from 'vuedraggable'

export default {
    components: {
        draggable
    },
    // ...
}
```

3. Actualizar template:
```html
<draggable v-model="regularProductImages" 
           class="row" 
           @end="updateImagePositions">
    <div class="img-container col-md-2 mb-3" 
         v-for="(image, index_image) in regularProductImages"
         :key="`imagen-regular-${image.id || index_image}`">
        <!-- ... contenido existente ... -->
    </div>
</draggable>
```

4. Agregar método:
```javascript
updateImagePositions() {
    // Actualizar posiciones en el backend
    this.regularProductImages.forEach((image, index) => {
        if (image.id) {
            axios.post('/admin/catalogo/producto-imagen/update-position', {
                image_id: image.id,
                position: index
            });
        }
    });
}
```

### 2. Validación de Imágenes
- Validar dimensiones mínimas/máximas
- Validar relación de aspecto
- Optimización automática de imágenes

### 3. Vista Previa Mejorada
- Lightbox al hacer clic en imagen
- Zoom sobre imagen
- Edición básica (crop, rotate)

## Testing

Para probar la funcionalidad:

1. Crear un producto regular:
   - Ir a `/admin/catalogo/productos/create`
   - Marcar "Es producto regular"
   - Llenar campos básicos
   - Ir al tab "Multimedia"
   - Arrastrar imágenes
   - Verificar que se suben inmediatamente
   - Verificar que aparecen en la grilla
   - Establecer una como principal
   - Guardar producto

2. Editar un producto regular existente:
   - Abrir un producto regular
   - Verificar que las imágenes existentes se muestran
   - Agregar nuevas imágenes
   - Eliminar una imagen
   - Cambiar imagen principal
   - Guardar cambios

3. Verificar que productos no regulares siguen funcionando igual

## Archivos Modificados

1. `database/migrations/2025_12_17_000000_add_position_to_products_images_table.php` - Nueva migración
2. `app/Models/ProductImage.php` - Agregado `position` a fillable
3. `app/Http/Controllers/admin/catalog/ProductImageController.php` - Retorna `url_img`
4. `resources/js/components/catalog/ProductFormComponent.vue` - Migración completa a vue-dropzone
5. `resources/views/dashboard/catalog/products/js/form.blade.php` - Eliminado Dropzone.js

## Compatibilidad

- ✅ Productos regulares existentes siguen funcionando
- ✅ Productos no regulares no se ven afectados
- ✅ Las imágenes existentes se muestran correctamente
- ✅ El campo `position` tiene valor default (0) para imágenes existentes

## Notas de Migración

Si hay productos con imágenes existentes:
- No se requiere migración de datos
- Las imágenes existentes se cargarán automáticamente
- El campo `position` será 0 por defecto
- Si se implementa reordering, se puede ejecutar un script para asignar posiciones basadas en created_at

## Conclusión

Esta implementación cumple con los objetivos del issue:
- ✅ Carga asíncrona de imágenes para productos regulares
- ✅ Uso de vue-dropzone (mismo componente que productos no regulares)
- ✅ Feedback visual inmediato al cargar imágenes
- ✅ Capacidad de establecer imagen principal
- ✅ Base preparada para ordenamiento de imágenes (campo position)

La experiencia de usuario es ahora consistente entre productos regulares y no regulares, con carga asíncrona y gestión visual de imágenes.
