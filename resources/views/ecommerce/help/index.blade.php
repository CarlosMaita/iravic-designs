@extends('ecommerce.base')

@section('title', 'Ayuda - Guía de Compra')
@section('meta-description', 'Guía completa de compra en Iravic Designs. Aprende cómo comprar, pagar y recibir tus productos de manera fácil y segura.')
@section('meta-keywords', 'ayuda, guía de compra, soporte, iravic designs, cómo comprar')

@section('content')
<!-- Hero Section -->
<section class="bg-primary-subtle py-5">
  <div class="container py-md-4 py-lg-5">
    <div class="row align-items-center">
      <div class="col-lg-7 text-center text-lg-start">
        <h1 class="display-4 fw-bold mb-3">
          <i class="ci-help-circle text-primary me-2"></i>
          Centro de Ayuda
        </h1>
        <p class="lead text-body-secondary mb-4">
          Todo lo que necesitas saber para realizar tus compras en <strong>Iravic Designs</strong> de manera fácil y segura.
        </p>
      </div>
      <div class="col-lg-5 d-none d-lg-block text-end">
        <i class="ci-help-circle display-1 text-primary opacity-25"></i>
      </div>
    </div>
  </div>
</section>

<!-- Quick Links -->
<section class="container py-5">
  <div class="row g-4 mb-5">
    <div class="col-12">
      <h2 class="h3 mb-4 text-center">Acceso Rápido</h2>
    </div>
    <div class="col-md-6 col-lg-3">
      <a href="#que-es" class="card card-hover h-100 text-decoration-none border-0 shadow-sm">
        <div class="card-body text-center p-4">
          <div class="bg-primary-subtle rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
            <i class="ci-store fs-3 text-primary"></i>
          </div>
          <h3 class="h6 mb-2">¿Qué es Iravic Designs?</h3>
          <p class="fs-sm text-body-secondary mb-0">Conoce nuestra tienda</p>
        </div>
      </a>
    </div>
    <div class="col-md-6 col-lg-3">
      <a href="#como-comprar" class="card card-hover h-100 text-decoration-none border-0 shadow-sm">
        <div class="card-body text-center p-4">
          <div class="bg-success-subtle rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
            <i class="ci-shopping-cart fs-3 text-success"></i>
          </div>
          <h3 class="h6 mb-2">Cómo Comprar</h3>
          <p class="fs-sm text-body-secondary mb-0">Paso a paso</p>
        </div>
      </a>
    </div>
    <div class="col-md-6 col-lg-3">
      <a href="#como-pagar" class="card card-hover h-100 text-decoration-none border-0 shadow-sm">
        <div class="card-body text-center p-4">
          <div class="bg-info-subtle rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
            <i class="ci-credit-card fs-3 text-info"></i>
          </div>
          <h3 class="h6 mb-2">Cómo Pagar</h3>
          <p class="fs-sm text-body-secondary mb-0">Métodos disponibles</p>
        </div>
      </a>
    </div>
    <div class="col-md-6 col-lg-3">
      <a href="#preguntas-frecuentes" class="card card-hover h-100 text-decoration-none border-0 shadow-sm">
        <div class="card-body text-center p-4">
          <div class="bg-warning-subtle rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
            <i class="ci-help fs-3 text-warning"></i>
          </div>
          <h3 class="h6 mb-2">Preguntas Frecuentes</h3>
          <p class="fs-sm text-body-secondary mb-0">Respuestas rápidas</p>
        </div>
      </a>
    </div>
  </div>
</section>

<!-- What is Iravic Designs -->
<section id="que-es" class="container py-5">
  <div class="row">
    <div class="col-lg-10 mx-auto">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-4 p-md-5">
          <div class="d-flex align-items-center mb-4">
            <div class="bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
              <i class="ci-store fs-4 text-primary"></i>
            </div>
            <h2 class="h3 mb-0">¿Qué es Iravic Designs?</h2>
          </div>
          <p class="lead mb-4">
            <strong>Iravic Designs</strong> es una plataforma de comercio electrónico especializada en productos de calidad. Nuestra tienda en línea te permite:
          </p>
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <div class="d-flex">
                <i class="ci-check-circle text-success me-2 mt-1"></i>
                <span>Explorar un amplio catálogo de productos organizados por categorías</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex">
                <i class="ci-check-circle text-success me-2 mt-1"></i>
                <span>Realizar compras de forma segura y confiable</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex">
                <i class="ci-check-circle text-success me-2 mt-1"></i>
                <span>Pagar con múltiples métodos de pago</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex">
                <i class="ci-check-circle text-success me-2 mt-1"></i>
                <span>Realizar seguimiento de tus órdenes en tiempo real</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex">
                <i class="ci-check-circle text-success me-2 mt-1"></i>
                <span>Recibir tus productos a través de agencias de envío confiables</span>
              </div>
            </div>
          </div>
          
          <h3 class="h5 mb-3">Características Principales</h3>
          <ul class="list-unstyled mb-0">
            <li class="mb-2"><i class="ci-tag text-primary me-2"></i><strong>Catálogo Completo:</strong> Productos organizados por categorías y marcas</li>
            <li class="mb-2"><i class="ci-search text-primary me-2"></i><strong>Búsqueda Avanzada:</strong> Encuentra exactamente lo que buscas con filtros</li>
            <li class="mb-2"><i class="ci-user text-primary me-2"></i><strong>Cuenta de Usuario:</strong> Gestiona tus órdenes, favoritos y perfil</li>
            <li class="mb-2"><i class="ci-credit-card text-primary me-2"></i><strong>Múltiples Métodos de Pago:</strong> Flexibilidad para pagar como prefieras</li>
            <li class="mb-2"><i class="ci-package text-primary me-2"></i><strong>Seguimiento de Órdenes:</strong> Conoce el estado de tu pedido en todo momento</li>
            <li class="mb-2"><i class="ci-support text-primary me-2"></i><strong>Soporte al Cliente:</strong> Estamos aquí para ayudarte</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- How to Buy -->
<section id="como-comprar" class="bg-secondary-subtle py-5">
  <div class="container py-md-4">
    <div class="row">
      <div class="col-lg-10 mx-auto">
        <div class="d-flex align-items-center mb-4">
          <div class="bg-success-subtle rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
            <i class="ci-shopping-cart fs-4 text-success"></i>
          </div>
          <h2 class="h3 mb-0">Cómo Comprar</h2>
        </div>
        <p class="lead mb-5">Realizar una compra en Iravic Designs es un proceso simple y seguro. Sigue estos pasos:</p>
        
        <!-- Steps -->
        <div class="row g-4">
          <!-- Step 1 -->
          <div class="col-12">
            <div class="card border-0 shadow-sm">
              <div class="card-body p-4">
                <div class="d-flex">
                  <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 45px; height: 45px;">
                    <span class="fw-bold">1</span>
                  </div>
                  <div class="flex-grow-1">
                    <h3 class="h5 mb-3">Explorar el Catálogo</h3>
                    <ul class="list-unstyled mb-0">
                      <li class="mb-2"><i class="ci-arrow-right text-success me-2"></i>Navega por categorías usando el menú de navegación</li>
                      <li class="mb-2"><i class="ci-arrow-right text-success me-2"></i>Usa la barra de búsqueda para productos específicos</li>
                      <li class="mb-2"><i class="ci-arrow-right text-success me-2"></i>Aplica filtros por precio, marca, color u otras características</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Step 2 -->
          <div class="col-12">
            <div class="card border-0 shadow-sm">
              <div class="card-body p-4">
                <div class="d-flex">
                  <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 45px; height: 45px;">
                    <span class="fw-bold">2</span>
                  </div>
                  <div class="flex-grow-1">
                    <h3 class="h5 mb-3">Agregar Productos al Carrito</h3>
                    <ul class="list-unstyled mb-0">
                      <li class="mb-2"><i class="ci-arrow-right text-success me-2"></i>Haz clic en un producto para ver detalles completos</li>
                      <li class="mb-2"><i class="ci-arrow-right text-success me-2"></i>Revisa precio, descripción, imágenes y disponibilidad</li>
                      <li class="mb-2"><i class="ci-arrow-right text-success me-2"></i>Selecciona variantes si aplica (colores, tallas, etc.)</li>
                      <li class="mb-2"><i class="ci-arrow-right text-success me-2"></i>Elige la cantidad y haz clic en "Agregar al Carrito"</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Step 3 -->
          <div class="col-12">
            <div class="card border-0 shadow-sm">
              <div class="card-body p-4">
                <div class="d-flex">
                  <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 45px; height: 45px;">
                    <span class="fw-bold">3</span>
                  </div>
                  <div class="flex-grow-1">
                    <h3 class="h5 mb-3">Revisar tu Carrito</h3>
                    <ul class="list-unstyled mb-0">
                      <li class="mb-2"><i class="ci-arrow-right text-success me-2"></i>Haz clic en el ícono del carrito en la parte superior</li>
                      <li class="mb-2"><i class="ci-arrow-right text-success me-2"></i>Verifica productos y cantidades</li>
                      <li class="mb-2"><i class="ci-arrow-right text-success me-2"></i>Ajusta cantidades o elimina productos si es necesario</li>
                      <li class="mb-2"><i class="ci-arrow-right text-success me-2"></i>Revisa el monto total y haz clic en "Proceder con la Compra"</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Step 4 -->
          <div class="col-12">
            <div class="card border-0 shadow-sm">
              <div class="card-body p-4">
                <div class="d-flex">
                  <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 45px; height: 45px;">
                    <span class="fw-bold">4</span>
                  </div>
                  <div class="flex-grow-1">
                    <h3 class="h5 mb-3">Crear Cuenta o Iniciar Sesión</h3>
                    <div class="alert alert-warning d-flex align-items-start mb-3">
                      <i class="ci-info-circle fs-5 me-2 mt-1"></i>
                      <div>
                        <strong>Importante:</strong> Necesitas estar registrado e iniciar sesión para realizar compras.
                      </div>
                    </div>
                    <p class="mb-2"><strong>Si ya tienes cuenta:</strong></p>
                    <ul class="mb-3">
                      <li>Ingresa tu correo electrónico y contraseña</li>
                    </ul>
                    <p class="mb-2"><strong>Si no tienes cuenta:</strong></p>
                    <ul class="mb-3">
                      <li>Completa el formulario de registro con tus datos</li>
                      <li>O usa la opción "Continuar con Google" para mayor rapidez</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Step 5 -->
          <div class="col-12">
            <div class="card border-0 shadow-sm">
              <div class="card-body p-4">
                <div class="d-flex">
                  <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 45px; height: 45px;">
                    <span class="fw-bold">5</span>
                  </div>
                  <div class="flex-grow-1">
                    <h3 class="h5 mb-3">Proporcionar Información de Envío</h3>
                    <p class="mb-2"><strong>Datos requeridos:</strong></p>
                    <ul class="list-unstyled mb-2">
                      <li class="mb-2"><i class="ci-arrow-right text-success me-2"></i>Nombre completo</li>
                      <li class="mb-2"><i class="ci-arrow-right text-success me-2"></i>Cédula/DNI</li>
                      <li class="mb-2"><i class="ci-arrow-right text-success me-2"></i>Teléfono de contacto</li>
                      <li class="mb-2"><i class="ci-arrow-right text-success me-2"></i>Agencia de envío (MRW, ZOOM, Domesa)</li>
                      <li class="mb-2"><i class="ci-arrow-right text-success me-2"></i>Dirección de la agencia</li>
                    </ul>
                    <div class="alert alert-info d-flex align-items-start mb-0">
                      <i class="ci-info-circle fs-5 me-2 mt-1"></i>
                      <div>
                        <strong>Tip:</strong> Esta información se guardará en tu perfil para futuras compras.
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Step 6 -->
          <div class="col-12">
            <div class="card border-0 shadow-sm">
              <div class="card-body p-4">
                <div class="d-flex">
                  <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 45px; height: 45px;">
                    <span class="fw-bold">6</span>
                  </div>
                  <div class="flex-grow-1">
                    <h3 class="h5 mb-3">Confirmar tu Orden</h3>
                    <ul class="list-unstyled mb-0">
                      <li class="mb-2"><i class="ci-arrow-right text-success me-2"></i>Verifica que todos los datos sean correctos</li>
                      <li class="mb-2"><i class="ci-arrow-right text-success me-2"></i>Haz clic en "Crear Orden" o "Confirmar Compra"</li>
                      <li class="mb-2"><i class="ci-arrow-right text-success me-2"></i>Recibirás confirmación con el número de tu orden</li>
                      <li class="mb-2"><i class="ci-arrow-right text-success me-2"></i>Serás redirigido a la página donde podrás registrar el pago</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- How to Pay -->
<section id="como-pagar" class="container py-5">
  <div class="row">
    <div class="col-lg-10 mx-auto">
      <div class="d-flex align-items-center mb-4">
        <div class="bg-info-subtle rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
          <i class="ci-credit-card fs-4 text-info"></i>
        </div>
        <h2 class="h3 mb-0">Cómo Pagar</h2>
      </div>

      <!-- Payment Methods -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4 p-md-5">
          <h3 class="h5 mb-4">Métodos de Pago Disponibles</h3>
          <div class="row g-3">
            <div class="col-md-6">
              <div class="d-flex align-items-start p-3 border rounded">
                <i class="ci-phone fs-3 text-primary me-3"></i>
                <div>
                  <h4 class="h6 mb-1">Pago Móvil</h4>
                  <p class="fs-sm text-body-secondary mb-0">Transferencia desde tu banco móvil</p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-start p-3 border rounded">
                <i class="ci-building fs-3 text-primary me-3"></i>
                <div>
                  <h4 class="h6 mb-1">Transferencia Bancaria</h4>
                  <p class="fs-sm text-body-secondary mb-0">Desde cualquier banco</p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-start p-3 border rounded">
                <i class="ci-wallet fs-3 text-primary me-3"></i>
                <div>
                  <h4 class="h6 mb-1">Efectivo</h4>
                  <p class="fs-sm text-body-secondary mb-0">Pago directo en efectivo</p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-start p-3 border rounded">
                <i class="ci-bitcoin fs-3 text-primary me-3"></i>
                <div>
                  <h4 class="h6 mb-1">Binance</h4>
                  <p class="fs-sm text-body-secondary mb-0">Pago con criptomonedas</p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-start p-3 border rounded">
                <i class="ci-paypal fs-3 text-primary me-3"></i>
                <div>
                  <h4 class="h6 mb-1">PayPal</h4>
                  <p class="fs-sm text-body-secondary mb-0">Pago internacional</p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-start p-3 border rounded">
                <i class="ci-credit-card fs-3 text-primary me-3"></i>
                <div>
                  <h4 class="h6 mb-1">Tarjeta</h4>
                  <p class="fs-sm text-body-secondary mb-0">Crédito/Débito (cuando esté disponible)</p>
                </div>
              </div>
            </div>
          </div>
          <div class="alert alert-info d-flex align-items-start mt-4 mb-0">
            <i class="ci-info-circle fs-5 me-2 mt-1"></i>
            <div>
              <strong>Nota:</strong> Los métodos de pago activos pueden variar. Verás solo los métodos disponibles al momento de registrar tu pago.
            </div>
          </div>
        </div>
      </div>

      <!-- Payment Process -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4 p-md-5">
          <h3 class="h5 mb-4">Proceso de Pago</h3>
          <ol class="ps-3">
            <li class="mb-3">
              <strong>Acceder a tu Orden:</strong>
              <p class="mb-0">Ve a "Mis Órdenes" y haz clic en la orden que deseas pagar</p>
            </li>
            <li class="mb-3">
              <strong>Ver Detalles de Pago:</strong>
              <ul class="mt-2">
                <li>Monto pendiente en dólares (USD)</li>
                <li>Tasa de cambio del BCV (referencia)</li>
                <li>Equivalente aproximado en bolívares (VES)</li>
              </ul>
            </li>
            <li class="mb-3">
              <strong>Registrar tu Pago:</strong>
              <ul class="mt-2">
                <li>Selecciona el método de pago utilizado</li>
                <li>Completa los campos: fecha, referencia, monto, moneda</li>
                <li>Agrega comentarios si es necesario</li>
              </ul>
            </li>
            <li class="mb-3">
              <strong>Enviar Reporte:</strong>
              <p class="mb-0">Revisa la información y haz clic en "Enviar Reporte de Pago"</p>
            </li>
            <li class="mb-0">
              <strong>Verificación:</strong>
              <p class="mb-0">Nuestro equipo verificará tu pago y recibirás una notificación</p>
            </li>
          </ol>
        </div>
      </div>

      <!-- Currency Info -->
      <div class="card border-0 shadow-sm">
        <div class="card-body p-4 p-md-5">
          <h3 class="h5 mb-4">Moneda y Tasa de Cambio</h3>
          
          <div class="mb-4">
            <h4 class="h6 mb-2"><i class="ci-dollar-circle text-success me-2"></i>Sistema de Precios en USD</h4>
            <ul class="mb-0">
              <li>Todos los precios están en dólares americanos (USD)</li>
              <li>La contabilidad se maneja en USD para precios estables</li>
            </ul>
          </div>

          <div class="mb-0">
            <h4 class="h6 mb-2"><i class="ci-currency-exchange text-primary me-2"></i>Pagos en Bolívares (VES)</h4>
            <ul class="mb-3">
              <li>Usa la <strong>tasa oficial del Banco Central de Venezuela (BCV)</strong></li>
              <li>En el modal de pago verás el equivalente calculado con la tasa del BCV</li>
              <li>El sistema convertirá automáticamente tu pago en VES a USD</li>
            </ul>
            <div class="alert alert-warning d-flex align-items-start mb-0">
              <i class="ci-alert-triangle fs-5 me-2 mt-1"></i>
              <div>
                <strong>Advertencia:</strong> Siempre verifica la tasa de cambio oficial del BCV antes de realizar tu pago en bolívares. La tasa mostrada es referencial y puede variar.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Orders Status -->
<section id="tus-pedidos" class="bg-secondary-subtle py-5">
  <div class="container py-md-4">
    <div class="row">
      <div class="col-lg-10 mx-auto">
        <div class="d-flex align-items-center mb-4">
          <div class="bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
            <i class="ci-package fs-4 text-primary"></i>
          </div>
          <h2 class="h3 mb-0">Estados de una Orden</h2>
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                  <div class="bg-warning-subtle rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="ci-file-text fs-5 text-warning"></i>
                  </div>
                  <h3 class="h6 mb-0">Creada</h3>
                </div>
                <ul class="fs-sm mb-0">
                  <li>Orden creada exitosamente</li>
                  <li>Pendiente de pago</li>
                  <li>Puedes registrar pagos</li>
                  <li>Puedes cancelar en este estado</li>
                </ul>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                  <div class="bg-success-subtle rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="ci-check-circle fs-5 text-success"></i>
                  </div>
                  <h3 class="h6 mb-0">Pagada</h3>
                </div>
                <ul class="fs-sm mb-0">
                  <li>Pago verificado</li>
                  <li>Orden siendo preparada</li>
                  <li>Será enviada próximamente</li>
                </ul>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                  <div class="bg-info-subtle rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="ci-delivery fs-5 text-info"></i>
                  </div>
                  <h3 class="h6 mb-0">Enviada</h3>
                </div>
                <ul class="fs-sm mb-0">
                  <li>Enviado a la agencia</li>
                  <li>Recibirás número de guía</li>
                  <li>Puedes retirar en la agencia</li>
                </ul>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                  <div class="bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="ci-star fs-5 text-primary"></i>
                  </div>
                  <h3 class="h6 mb-0">Completada</h3>
                </div>
                <ul class="fs-sm mb-0">
                  <li>Producto recibido</li>
                  <li>Orden finalizada</li>
                  <li>¡Gracias por tu compra!</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FAQ -->
<section id="preguntas-frecuentes" class="container py-5">
  <div class="row">
    <div class="col-lg-10 mx-auto">
      <div class="d-flex align-items-center mb-4">
        <div class="bg-warning-subtle rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
          <i class="ci-help fs-4 text-warning"></i>
        </div>
        <h2 class="h3 mb-0">Preguntas Frecuentes</h2>
      </div>

      <div class="accordion" id="faqAccordion">
        <!-- General -->
        <div class="accordion-item border-0 shadow-sm mb-3">
          <h3 class="accordion-header" id="faqHeading1">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1">
              <i class="ci-help-circle text-primary me-2"></i>
              ¿Necesito crear una cuenta para comprar?
            </button>
          </h3>
          <div id="faqCollapse1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Sí, debes registrarte e iniciar sesión para realizar compras. Esto te permite hacer seguimiento de tus órdenes y gestionar tu perfil.
            </div>
          </div>
        </div>

        <div class="accordion-item border-0 shadow-sm mb-3">
          <h3 class="accordion-header" id="faqHeading2">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2">
              <i class="ci-google text-danger me-2"></i>
              ¿Puedo usar mi cuenta de Google para registrarme?
            </button>
          </h3>
          <div id="faqCollapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Sí, ofrecemos la opción de registro e inicio de sesión con Google para mayor comodidad.
            </div>
          </div>
        </div>

        <div class="accordion-item border-0 shadow-sm mb-3">
          <h3 class="accordion-header" id="faqHeading3">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3">
              <i class="ci-dollar-circle text-success me-2"></i>
              ¿En qué moneda están los precios?
            </button>
          </h3>
          <div id="faqCollapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Todos los precios están en dólares americanos (USD). Sin embargo, puedes realizar el pago en bolívares usando la tasa del BCV.
            </div>
          </div>
        </div>

        <div class="accordion-item border-0 shadow-sm mb-3">
          <h3 class="accordion-header" id="faqHeading4">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4">
              <i class="ci-time text-info me-2"></i>
              ¿Cuánto tarda en verificarse mi pago?
            </button>
          </h3>
          <div id="faqCollapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Los pagos son verificados por nuestro equipo, generalmente en 24-48 horas laborables.
            </div>
          </div>
        </div>

        <div class="accordion-item border-0 shadow-sm mb-3">
          <h3 class="accordion-header" id="faqHeading5">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse5">
              <i class="ci-delivery text-primary me-2"></i>
              ¿A dónde envían?
            </button>
          </h3>
          <div id="faqCollapse5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Enviamos a través de agencias de envío (MRW, ZOOM, Domesa). Debes recoger tu pedido en la agencia seleccionada.
            </div>
          </div>
        </div>

        <div class="accordion-item border-0 shadow-sm mb-3">
          <h3 class="accordion-header" id="faqHeading6">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse6">
              <i class="ci-close-circle text-danger me-2"></i>
              ¿Cómo puedo cancelar mi orden?
            </button>
          </h3>
          <div id="faqCollapse6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Solo puedes cancelar órdenes en estado "Creada" (antes de ser pagada). Ve a la orden y haz clic en "Cancelar Orden". Para órdenes pagadas, contacta al soporte.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Contact Support -->
<section class="bg-primary-subtle py-5">
  <div class="container py-md-4">
    <div class="row">
      <div class="col-lg-8 mx-auto text-center">
        <i class="ci-support display-4 text-primary mb-4"></i>
        <h2 class="h3 mb-3">¿Necesitas Ayuda Adicional?</h2>
        <p class="lead mb-4">Si tienes alguna duda o necesitas asistencia, nuestro equipo está aquí para ayudarte.</p>
        <div class="row g-3 justify-content-center">
          <!-- <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body text-center p-4">
                <i class="ci-mail fs-1 text-primary mb-3"></i>
                <h3 class="h6 mb-2">Email</h3>
                <a href="mailto:soporte@iravicdesigns.com" class="text-decoration-none">soporte@iravicdesigns.com</a>
              </div>
            </div>
          </div> -->
          <!-- <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body text-center p-4">
                <i class="ci-github fs-1 text-primary mb-3"></i>
                <h3 class="h6 mb-2">GitHub</h3>
                <a href="https://github.com/CarlosMaita/iravic-designs/issues" target="_blank" class="text-decoration-none">Reportar Issue</a>
              </div>
            </div>
          </div> -->
          <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body text-center p-4">
                <i class="ci-whatsapp fs-1 text-success mb-3"></i>
                <h3 class="h6 mb-2">WhatsApp</h3>
                <a href="https://wa.me/584144519511" target="_blank" class="text-decoration-none">Contactar</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Tips Section -->
<section class="container py-5">
  <div class="row">
    <div class="col-lg-10 mx-auto">
      <div class="card border-0 shadow-sm bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body p-4 p-md-5 text-white">
          <h2 class="h3 mb-4"><i class="ci-star me-2"></i>Consejos para una Compra Exitosa</h2>
          <div class="row g-3">
            <div class="col-md-6">
              <div class="d-flex align-items-start">
                <i class="ci-check-circle fs-4 me-2 mt-1 opacity-75"></i>
                <span>Verifica tu información de contacto y envío</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-start">
                <i class="ci-check-circle fs-4 me-2 mt-1 opacity-75"></i>
                <span>Revisa tu carrito antes de confirmar</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-start">
                <i class="ci-check-circle fs-4 me-2 mt-1 opacity-75"></i>
                <span>Usa la tasa BCV si pagas en bolívares</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-start">
                <i class="ci-check-circle fs-4 me-2 mt-1 opacity-75"></i>
                <span>Guarda tus comprobantes de pago</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-start">
                <i class="ci-check-circle fs-4 me-2 mt-1 opacity-75"></i>
                <span>Registra tu pago lo antes posible</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-start">
                <i class="ci-check-circle fs-4 me-2 mt-1 opacity-75"></i>
                <span>Guarda el número de guía de envío</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
