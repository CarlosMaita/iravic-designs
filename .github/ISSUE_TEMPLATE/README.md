# Plantillas de Issues - Iravic Designs

Este directorio contiene las plantillas de issues configuradas para el repositorio de Iravic Designs. Estas plantillas están diseñadas para estandarizar la forma en que se reportan bugs, se solicitan nuevas funcionalidades y se pide documentación.

## 📋 Plantillas Disponibles

### 🔧 Ajuste Rápido (`quick_fix.yml`)

Plantilla diseñada para solicitar ajustes rápidos y reparaciones menores que no requieren un desarrollo extenso.

**Secciones incluidas:**
- **Título y descripción del ajuste**: Información clara sobre qué necesita modificarse
- **Evidencia en imágenes**: Capturas de pantalla del problema y resultado esperado
- **Tipo de ajuste**: Clasificación (Visual/Estilo, Contenido, Imágenes, Enlaces, etc.)
- **Ubicación/Módulo**: Dónde se encuentra el elemento a ajustar
- **Urgencia**: Nivel de prioridad del ajuste
- **Alcance del dispositivo**: En qué dispositivos se presenta el problema
- **Comportamiento actual vs esperado**: Descripción clara del antes y después
- **Ubicación específica**: URL, ruta de archivo, selectores CSS
- **Solución sugerida**: Propuesta opcional de cómo resolverlo
- **Navegadores probados**: Lista de verificación de navegadores
- **Esfuerzo estimado**: Tiempo aproximado que tomará el ajuste

**Casos de uso ideales:**
- Correcciones visuales menores (alineación, espaciado, colores)
- Ajustes de texto o contenido
- Pequeñas modificaciones de estilo CSS
- Corrección de enlaces rotos
- Optimizaciones de responsive design
- Mejoras de accesibilidad menores
- Reparaciones que no afectan la lógica del negocio

### 🚀 Solicitud de Nueva Funcionalidad (`feature_request.yml`)

Plantilla completa para proponer el desarrollo de nuevas funcionalidades en el sistema e-commerce Iravic Designs.

**Secciones incluidas:**
- **Información básica**: Nombre y descripción detallada
- **Objetivos y metas**: Definición clara de lo que debe lograr la funcionalidad
- **Prioridad y módulo**: Clasificación dentro del sistema
- **Historias de usuario**: Casos de uso desde la perspectiva del usuario
- **Requisitos técnicos**: Especificaciones de implementación (Laravel, Vue.js, Base de datos)
- **Criterios de aceptación**: Lista de verificación para considerar completada la funcionalidad
- **Análisis de impacto**: Áreas del sistema que podrían verse afectadas
- **Referencias visuales**: Mockups y ejemplos de diseño
- **Dependencias**: Issues relacionados y prerrequisitos
- **Complejidad estimada**: Evaluación del esfuerzo requerido

### 🐛 Reporte de Error (`bug_report.yml`)

Plantilla estructurada para reportar bugs y errores del sistema.

**Secciones incluidas:**
- **Descripción del error**: Resumen y descripción detallada
- **Reproducción**: Pasos específicos para reproducir el problema
- **Comportamiento**: Esperado vs. actual
- **Severidad y frecuencia**: Clasificación del impacto
- **Información del entorno**: SO, navegador, versiones de tecnologías
- **Logs de error**: Mensajes de error y capturas de pantalla
- **Módulo afectado**: Identificación del área del sistema
- **Pasos de resolución**: Intentos previos de solución

### 📚 Solicitud de Documentación (`documentation_request.yml`)

Plantilla para solicitar mejoras o creación de documentación.

**Secciones incluidas:**
- **Tipo de documentación**: Nueva, actualización, técnica, usuario, etc.
- **Audiencia objetivo**: Desarrolladores, administradores, usuarios finales
- **Esquema de contenido**: Estructura sugerida
- **Recursos existentes**: Documentación relacionada
- **Ejemplos y referencias**: Casos de uso y documentación similar
- **Requisitos específicos**: Elementos que debe incluir
- **Formato preferido**: Markdown, Wiki, PDF, etc.
- **Oferta de contribución**: Disposición para ayudar en la creación

## ⚙️ Configuración (`config.yml`)

Archivo de configuración que:
- Deshabilita issues en blanco (`blank_issues_enabled: false`)
- Define enlaces de contacto para:
  - Documentación del proyecto
  - Discusiones generales
  - Estándares de codificación
  - Guía de implementación
  - Contacto directo por email

## 🎯 Objetivos de las Plantillas

### 1. **Estandarización**
- Formato consistente para todos los tipos de issues
- Información completa y estructurada
- Facilita la revisión y categorización

### 2. **Eficiencia**
- Reduce el tiempo de clarificación
- Información necesaria desde el primer reporte
- Clasificación automática mediante labels

### 3. **Calidad del Desarrollo**
- Requisitos técnicos específicos para Laravel y Vue.js
- Consideración de la arquitectura existente (Cartzilla)
- Integración con estándares de codificación del proyecto

### 4. **Trazabilidad**
- Análisis de impacto en diferentes módulos
- Relación con issues existentes
- Criterios de aceptación claros

## 🚀 Cómo Usar las Plantillas

1. **Crear nuevo issue**: Ve a la sección Issues del repositorio
2. **Seleccionar plantilla**: GitHub mostrará automáticamente las opciones disponibles
3. **Completar información**: Llena todos los campos obligatorios
4. **Revisar antes de enviar**: Verifica que la información esté completa
5. **Seguimiento**: Mantente disponible para aclaraciones

## 📝 Convenciones de Etiquetado

### Labels Automáticos:
- `enhancement` + `nueva-funcionalidad` → Solicitudes de funcionalidades
- `bug` + `needs-investigation` → Reportes de errores  
- `documentation` + `enhancement` → Solicitudes de documentación
- `quick-fix` + `ajuste-rápido` → Ajustes rápidos y reparaciones menores

### Prefijos de Títulos:
- `[FEATURE]` → Nueva funcionalidad
- `[BUG]` → Reporte de error
- `[DOCS]` → Documentación
- `[QUICK-FIX]` → Ajuste rápido

## 🔧 Mantenimiento

Las plantillas deben actualizarse cuando:
- Se agreguen nuevos módulos al sistema
- Cambien las tecnologías principales (Laravel, Vue.js)
- Se modifiquen los estándares de codificación
- Se identifiquen campos faltantes en los reportes

## 📚 Referencias

- [GitHub Issue Templates Documentation](https://docs.github.com/en/communities/using-templates-to-encourage-useful-issues-and-pull-requests/about-issue-and-pull-request-templates)
- [YAML Syntax Guide](https://docs.ansible.com/ansible/latest/reference_appendices/YAMLSyntax.html)
- [Estándares de Codificación del Proyecto](../CODING_STANDARDS.md)
- [Guía de Implementación del Proyecto](../IMPLEMENTATION_GUIDE.md)

---

**Desarrollado para Iravic Designs - Sistema E-commerce con Laravel y Vue.js** 🚀