# Guía de Compilación de Node.js

Este documento describe cómo compilar los assets de frontend para el proyecto Iravic Designs.

## Requisitos Previos

- Node.js 20.x o superior (probado con v20.19.6)
- npm 10.x o superior (probado con v10.8.2)

## Instalación de Dependencias

Antes de compilar los assets, debes instalar las dependencias de Node.js:

```bash
npm install --ignore-scripts
```

**Nota:** El flag `--ignore-scripts` se usa para evitar problemas de compatibilidad con node-sass en Node.js 20+. La instalación puede tardar entre 8-10 minutos y mostrará muchas advertencias de deprecación, lo cual es normal para este proyecto.

## Compilación para Desarrollo

Para compilar los assets en modo de desarrollo (con source maps y sin minificación):

```bash
npm run dev
```

Este comando compila:
- `/resources/js/app.js` → `/public/js/app.js` (Admin panel)
- `/resources/js/ecommerce/app.js` → `/public/js/ecommerce/app.js` (Frontend de e-commerce)

## Compilación para Producción

Para compilar los assets optimizados para producción (minificados y sin source maps):

```bash
npm run prod
```

Los assets compilados se generan en:
- `/public/js/app.js` (~1.05 MB minificado)
- `/public/js/ecommerce/app.js` (~533 KB minificado)

## Modo de Observación (Watch)

Para recompilar automáticamente cuando se detecten cambios en los archivos fuente:

```bash
npm run watch
```

## Corrección de Errores Comunes

### Error: "Node Sass does not yet support your current environment"

Este error ocurre porque el proyecto usa `node-sass` que no es compatible con Node.js 20+. La solución es usar el flag `--ignore-scripts` durante la instalación.

### Error de Plantilla Vue: "Component template should contain exactly one root element"

Si encuentras errores de sintaxis en componentes Vue, verifica que:
1. Cada componente tenga exactamente un elemento raíz en su template
2. Todas las etiquetas HTML estén correctamente balanceadas (mismo número de tags de apertura y cierre)
3. No haya divs de cierre extras

## Archivos Compilados

Los siguientes archivos son generados por el proceso de compilación y NO deben editarse manualmente:

- `/public/js/app.js`
- `/public/js/app.js.LICENSE.txt`
- `/public/js/ecommerce/app.js`
- `/public/js/ecommerce/app.js.LICENSE.txt`

Cualquier cambio en estos archivos se sobrescribirá en la próxima compilación. Para hacer cambios, edita los archivos fuente en `/resources/js/`.

## Estructura de Archivos Fuente

- `/resources/js/app.js` - Punto de entrada para el admin panel
- `/resources/js/ecommerce/app.js` - Punto de entrada para el frontend de e-commerce
- `/resources/js/components/` - Componentes Vue reutilizables
- `/webpack.mix.js` - Configuración de Laravel Mix para la compilación

## Troubleshooting

Si la compilación falla:

1. Verifica que todas las dependencias estén instaladas: `npm install --ignore-scripts`
2. Limpia los archivos compilados: `rm -rf public/js/app.js public/js/ecommerce/app.js`
3. Intenta compilar nuevamente: `npm run dev`
4. Si persisten los errores, revisa los logs para identificar problemas de sintaxis en componentes Vue

## Notas Adicionales

- El proyecto usa Laravel Mix (basado en Webpack) para la compilación
- Los source maps solo se generan en modo desarrollo
- Los archivos `.LICENSE.txt` contienen las licencias de las dependencias de terceros incluidas en los bundles
- Se recomienda ejecutar `npm run prod` antes de deployar a producción

## Solución de Problemas Específicos del Proyecto

Durante el desarrollo de este proyecto, se identificó y corrigió un error en el componente `Devolution.vue` donde había dos etiquetas `</div>` extras que causaban un desbalance en la estructura HTML. Si encuentras errores similares, usa herramientas de validación HTML o scripts de Python para contar las etiquetas de apertura y cierre.
