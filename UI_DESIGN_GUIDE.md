# Manual de Diseño UI del Administrador - Iravic Designs

## 📋 Tabla de Contenidos
- [Introducción](#introducción)
- [Paleta de Colores](#paleta-de-colores)
- [Tipografía](#tipografía)
- [Componentes](#componentes)
- [Espaciado y Layout](#espaciado-y-layout)
- [Guías de Uso](#guías-de-uso)
- [Ejemplos de Código](#ejemplos-de-código)

---

## 🎨 Introducción

Este manual de diseño define los estándares visuales y de experiencia de usuario para el panel de administración de Iravic Designs. El objetivo es mantener una interfaz consistente, profesional, moderna y tecnológica.

### Principios de Diseño
- **Modernidad**: Uso de colores vibrantes y elementos visuales contemporáneos
- **Claridad**: Jerarquía visual clara y navegación intuitiva
- **Consistencia**: Elementos uniformes en toda la aplicación
- **Accesibilidad**: Contraste adecuado y facilidad de uso

---

## 🎨 Paleta de Colores

### Colores Primarios

#### Primary (Indigo)
- **Código**: `#6366f1`
- **Uso**: Botones principales, enlaces destacados, elementos de navegación activos
- **Ejemplo**: Sidebar brand, botones primarios

#### Secondary (Púrpura)
- **Código**: `#8b5cf6`
- **Uso**: Elementos secundarios, acentos complementarios
- **Ejemplo**: Gradientes con primary

#### Accent (Azul Brillante)
- **Código**: `#3b82f6`
- **Uso**: Elementos de información, íconos interactivos
- **Ejemplo**: Botones info, alerts de información

### Colores de Estado

#### Success (Verde Esmeralda)
- **Código**: `#10b981`
- **Uso**: Mensajes de éxito, confirmaciones, estados positivos
- **Ejemplo**: Alerts de éxito, botones de confirmación

#### Warning (Ámbar)
- **Código**: `#f59e0b`
- **Uso**: Advertencias, mensajes informativos importantes
- **Ejemplo**: Alerts de advertencia, indicadores de precaución

#### Danger (Rojo Moderno)
- **Código**: `#ef4444`
- **Uso**: Errores, acciones destructivas, estados críticos
- **Ejemplo**: Botones de eliminar, alerts de error

#### Info (Cyan)
- **Código**: `#06b6d4`
- **Uso**: Mensajes informativos, tooltips
- **Ejemplo**: Alerts de información

### Colores Neutros

#### Dark (Slate Oscuro)
- **Código**: `#1e293b`
- **Uso**: Sidebar, textos principales
- **Ejemplo**: Background de la sidebar

#### Light (Slate Claro)
- **Código**: `#f8fafc`
- **Uso**: Backgrounds suaves, áreas de contenido
- **Ejemplo**: Subheader background

#### Gris Medio
- **Código**: `#64748b`
- **Uso**: Textos secundarios, iconos inactivos
- **Ejemplo**: Enlaces del header, breadcrumb

### Gradientes

Los gradientes se usan para dar profundidad y modernidad:

```css
/* Gradiente Principal */
background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);

/* Gradiente de Éxito */
background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);

/* Gradiente de Peligro */
background: linear-gradient(135deg, #ef4444 0%, #ec4899 100%);

/* Gradiente de Información */
background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);

/* Gradiente de Advertencia */
background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
```

---

## ✍️ Tipografía

### Fuente Principal
```css
font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 
             'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 
             'Droid Sans', 'Helvetica Neue', sans-serif;
```

### Tamaños de Texto
- **Base**: `0.9rem` (14.4px)
- **Pequeño**: `0.75rem` (12px) - Para labels, badges
- **Mediano**: `1rem` (16px) - Para textos regulares
- **Grande**: `1.25rem` (20px) - Para subtítulos
- **Extra Grande**: `1.5rem` (24px) - Para títulos

### Pesos de Fuente
- **Regular**: `400` - Texto normal
- **Medium**: `500` - Botones, elementos interactivos
- **Semi-Bold**: `600` - Títulos, encabezados de cards, labels
- **Bold**: `700` - Títulos principales

---

## 🧩 Componentes

### 1. Cards (Tarjetas)

Las cards son contenedores principales para agrupar información.

**Características:**
- Border radius: `0.75rem` (12px)
- Sin bordes
- Sombra suave que se eleva en hover
- Transición suave de 0.3s

**HTML Ejemplo:**
```html
<div class="card">
    <div class="card-header">
        <i class="fa fa-align-justify"></i> Título de la Card
    </div>
    <div class="card-body">
        Contenido de la card
    </div>
</div>
```

**Estilos Aplicados:**
- Header con gradiente indigo-púrpura
- Texto blanco en header
- Padding: `1rem 1.5rem` en header
- Padding: `1.5rem` en body
- Hover: Elevación y sombra más pronunciada

### 2. Botones

Los botones usan gradientes para dar profundidad visual.

**Variantes:**
```html
<button class="btn btn-primary">Primario</button>
<button class="btn btn-success">Éxito</button>
<button class="btn btn-danger">Peligro</button>
<button class="btn btn-info">Información</button>
<button class="btn btn-warning">Advertencia</button>
```

**Características:**
- Border radius: `0.5rem` (8px)
- Sin bordes
- Font-weight: `500`
- Transición suave con elevación en hover
- Gradientes para cada variante

### 3. Alerts (Alertas)

Mensajes de feedback al usuario con diseño moderno.

**Variantes:**
```html
<div class="alert alert-info">Mensaje informativo</div>
<div class="alert alert-success">Mensaje de éxito</div>
<div class="alert alert-warning">Mensaje de advertencia</div>
<div class="alert alert-danger">Mensaje de error</div>
```

**Características:**
- Border radius: `0.75rem`
- Borde izquierdo de 4px con color de estado
- Background semi-transparente
- Sin bordes en otros lados

### 4. Tables (Tablas)

Tablas con diseño moderno y hover interactivo.

**Características:**
- Headers con gradiente indigo-púrpura
- Texto blanco en headers
- Headers en mayúsculas con letter-spacing
- Hover en filas con background semi-transparente
- Ligera escala en hover (1.01)

**HTML Ejemplo:**
```html
<table class="table">
    <thead>
        <tr>
            <th>Columna 1</th>
            <th>Columna 2</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Dato 1</td>
            <td>Dato 2</td>
        </tr>
    </tbody>
</table>
```

### 5. Badges

Indicadores visuales para estados o categorías.

**Variantes:**
```html
<span class="badge badge-primary">Primary</span>
<span class="badge badge-success">Success</span>
<span class="badge badge-danger">Danger</span>
<span class="badge badge-info">Info</span>
<span class="badge badge-warning">Warning</span>
```

**Características:**
- Border radius: `0.375rem` (6px)
- Font-weight: `600`
- Padding: `0.35em 0.65em`
- Gradientes según variante

### 6. Forms (Formularios)

Campos de formulario con enfoque visual claro.

**HTML Ejemplo:**
```html
<div class="mb-3">
    <label class="form-label">Nombre del Campo</label>
    <input type="text" class="form-control" placeholder="Ingrese valor">
</div>
```

**Características:**
- Border radius: `0.5rem`
- Borde de 2px sólido (`#e2e8f0`)
- Focus: Borde indigo con sombra suave
- Labels con font-weight: `600`

### 7. Sidebar (Barra Lateral)

**Características:**
- Background: `#1e293b` (slate oscuro)
- Sombra lateral sutil
- Brand con gradiente indigo-púrpura
- Hover en links con borde izquierdo indigo
- Links activos con background gradiente sutil
- Íconos en gris claro que se vuelven indigo al activar/hover

**Comportamiento:**
- Transiciones suaves de 0.3s
- Borde izquierdo de 3px en hover
- Minimizer con gradiente

### 8. Header (Encabezado)

**Características:**
- Background blanco
- Sombra suave inferior
- Links en gris que se vuelven indigo en hover
- Subheader con background muy claro

### 9. Dropdown Menus

**Características:**
- Sin bordes
- Border radius: `0.75rem`
- Sombra elevada
- Items con padding generoso
- Hover con background indigo semi-transparente
- Headers con gradiente y texto en mayúsculas

---

## 📐 Espaciado y Layout

### Sistema de Espaciado

Usamos múltiplos de 0.25rem (4px):

- **xs**: `0.25rem` (4px)
- **sm**: `0.5rem` (8px)
- **md**: `1rem` (16px)
- **lg**: `1.5rem` (24px)
- **xl**: `2rem` (32px)
- **2xl**: `3rem` (48px)

### Border Radius

- **Pequeño**: `0.375rem` (6px) - Badges
- **Mediano**: `0.5rem` (8px) - Botones, inputs
- **Grande**: `0.75rem` (12px) - Cards, alerts, dropdowns

### Sombras

```css
/* Sombra Suave (Cards) */
box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);

/* Sombra Elevada (Cards hover, Dropdowns) */
box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);

/* Sombra de Botón en Hover */
box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
```

---

## 📚 Guías de Uso

### Cuándo Usar Cada Color

#### Primary (Indigo)
- Acciones principales (guardar, crear, enviar)
- Navegación activa
- Enlaces importantes

#### Success (Verde)
- Confirmaciones exitosas
- Estados activos/habilitados
- Botones de aprobación

#### Danger (Rojo)
- Acciones destructivas (eliminar, cancelar)
- Errores críticos
- Advertencias de pérdida de datos

#### Info (Cyan)
- Mensajes informativos
- Ayudas y tooltips
- Información adicional

#### Warning (Ámbar)
- Advertencias que requieren atención
- Estados pendientes
- Confirmaciones antes de acciones importantes

### Jerarquía Visual

1. **Títulos de Página**: Card headers con gradiente
2. **Subtítulos**: Texto en font-weight 600
3. **Contenido**: Texto regular
4. **Metadatos**: Texto en gris medio, tamaño pequeño

### Animaciones y Transiciones

Todas las transiciones deben ser suaves:
```css
transition: all 0.3s ease;
```

Animaciones de entrada:
```css
animation: fadeIn 0.5s ease;
```

---

## 💻 Ejemplos de Código

### Página de Lista Completa

```html
@extends('dashboard.base')

@section('content')
<div class="container-fluid">
    <div class="fade-in">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Lista de Elementos
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Elemento de Prueba</td>
                                    <td><span class="badge badge-success">Activo</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-info">Editar</button>
                                        <button class="btn btn-sm btn-danger">Eliminar</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

### Formulario de Creación/Edición

```html
@extends('dashboard.base')

@section('content')
<div class="container-fluid">
    <div class="fade-in">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-edit"></i> Crear/Editar Elemento
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('elemento.store') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea class="form-control" name="descripcion" rows="3"></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <select class="form-control" name="estado">
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                            
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('elemento.index') }}" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

### Dashboard con Estadísticas

```html
@extends('dashboard.base')

@section('content')
<div class="container-fluid">
    <div class="fade-in">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-primary">1,234</h3>
                        <p class="text-muted mb-0">Total Ventas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-success">567</h3>
                        <p class="text-muted mb-0">Productos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-info">89</h3>
                        <p class="text-muted mb-0">Clientes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-warning">12</h3>
                        <p class="text-muted mb-0">Pendientes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

## 🎯 Mejores Prácticas

### 1. Consistencia
- Usa siempre las clases predefinidas
- Mantén el mismo patrón de espaciado
- Respeta la paleta de colores establecida

### 2. Accesibilidad
- Asegura contraste suficiente (mínimo 4.5:1 para texto)
- Usa atributos ARIA cuando sea necesario
- Proporciona alternativas textuales para iconos

### 3. Responsividad
- Usa el sistema de grid de Bootstrap
- Prueba en diferentes tamaños de pantalla
- Usa clases responsive (col-md-, d-lg-none, etc.)

### 4. Performance
- Minimiza el uso de animaciones pesadas
- Usa transiciones CSS en lugar de JavaScript cuando sea posible
- Carga recursos de forma eficiente

### 5. Mantenibilidad
- Documenta cambios significativos
- Sigue la estructura de archivos establecida
- Comenta código complejo
- Reutiliza componentes existentes

---

## 🔄 Actualizaciones y Versionado

### Versión 1.0.0 (Diciembre 2024)
- Implementación inicial del diseño moderno
- Paleta de colores vibrante y tecnológica
- Componentes con gradientes
- Sistema de espaciado consistente
- Animaciones y transiciones suaves

---

## 📞 Soporte

Para dudas o sugerencias sobre el diseño UI, consulta:
- Este documento de guía
- Los archivos de estilos en `resources/sass/`
- El código de componentes existentes en `resources/views/`

---

**Última actualización**: Diciembre 2024
**Versión**: 1.0.0
**Autor**: Iravic Designs Team
