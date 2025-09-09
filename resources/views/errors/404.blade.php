@extends('layouts.cartzilla-error')

@section('title', 'Página no encontrada')

@section('content')
<!-- Page content -->
<main class="page-wrapper">

  <!-- Navbar (Minimal) -->
  <header class="navbar navbar-expand-lg navbar-light bg-white shadow-sm position-sticky top-0 z-fixed">
    <div class="container">
      <a class="navbar-brand pe-4 pe-sm-0" href="{{ url('/') }}">
        <h6 class="m-0 text-uppercase text-primary" style="font-family: Roboto, sans-serif; letter-spacing: 3px;">Iravic Designs</h6>
      </a>
      <div class="navbar-nav ms-auto">
        <a class="nav-link text-decoration-none" href="{{ url('/') }}">
          <i class="ci-home me-2"></i>Inicio
        </a>
      </div>
    </div>
  </header>

  <!-- 404 Hero Section -->
  <section class="container py-5 mt-4 mb-2 mb-md-4 mb-lg-5">
    <div class="row align-items-center py-md-3">
      
      <!-- Error Illustration -->
      <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
        <div class="position-relative text-center">
          <!-- 404 Illustration Background -->
          <div class="bg-primary bg-opacity-10 rounded-circle mx-auto mb-4" style="width: 320px; height: 320px; display: flex; align-items: center; justify-content: center;">
            <div class="text-center">
              <!-- Large 404 Number -->
              <div class="display-1 fw-bold text-primary mb-0" style="font-size: 5rem; line-height: 1;">404</div>
              <!-- Fashion Icon -->
              <div class="mt-3">
                <i class="ci-search text-primary" style="font-size: 2.5rem;"></i>
              </div>
            </div>
          </div>
          
          <!-- Decorative Elements -->
          <div class="position-absolute top-0 start-0">
            <div class="bg-warning rounded-circle opacity-25" style="width: 60px; height: 60px;"></div>
          </div>
          <div class="position-absolute top-50 end-0 translate-middle-y">
            <div class="bg-info rounded-circle opacity-25" style="width: 40px; height: 40px;"></div>
          </div>
          <div class="position-absolute bottom-0 start-50 translate-middle-x">
            <div class="bg-success rounded-circle opacity-25" style="width: 50px; height: 50px;"></div>
          </div>
        </div>
      </div>

      <!-- Error Content -->
      <div class="col-lg-6 order-lg-1">
        <div class="pe-lg-5">
          <!-- Main Heading -->
          <h1 class="display-4 fw-bold text-dark mb-3">
            ¡Oops! Página no encontrada
          </h1>
          
          <!-- Description -->
          <p class="fs-lg text-muted mb-4">
            Lo sentimos, la página que estás buscando no existe o ha sido movida. 
            Pero no te preocupes, tenemos muchas otras cosas increíbles para mostrarte.
          </p>

          <!-- Action Buttons -->
          <div class="d-flex flex-column flex-sm-row gap-3 mb-4">
            <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
              <i class="ci-home me-2"></i>
              Ir al Inicio
            </a>
            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-lg">
              <i class="ci-arrow-left me-2"></i>
              Volver Atrás
            </a>
          </div>

          <!-- Quick Links -->
          <div class="border-top pt-4">
            <h6 class="fw-semibold text-dark mb-3">Enlaces útiles:</h6>
            <div class="row g-3">
              <div class="col-sm-6">
                <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none text-dark">
                  <i class="ci-grid text-primary me-2"></i>
                  <span>Catálogo de Productos</span>
                </a>
              </div>
              <div class="col-sm-6">
                <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none text-dark">
                  <i class="ci-user text-primary me-2"></i>
                  <span>Mi Cuenta</span>
                </a>
              </div>
              <div class="col-sm-6">
                <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none text-dark">
                  <i class="ci-heart text-primary me-2"></i>
                  <span>Lista de Deseos</span>
                </a>
              </div>
              <div class="col-sm-6">
                <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none text-dark">
                  <i class="ci-message-circle text-primary me-2"></i>
                  <span>Soporte</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>

<!-- Footer -->
<footer class="footer bg-dark mt-auto">
  <div class="container py-4">
    <div class="row align-items-center">
      <div class="col-md-6">
        <p class="text-light opacity-75 mb-0">
          &copy; {{ date('Y') }} Iravic Designs. Todos los derechos reservados.
        </p>
      </div>
      <div class="col-md-6 text-md-end">
        <div class="d-flex justify-content-md-end gap-3 mt-3 mt-md-0">
          <a href="{{ url('/') }}" class="text-light text-decoration-none opacity-75 hover-opacity-100">
            Política de Privacidad
          </a>
          <a href="{{ url('/') }}" class="text-light text-decoration-none opacity-75 hover-opacity-100">
            Términos de Uso
          </a>
        </div>
      </div>
    </div>
  </div>
</footer>

@endsection

@push('css')
<style>
  .hover-opacity-100:hover {
    opacity: 1 !important;
  }
  
  .z-fixed {
    z-index: 1030;
  }
</style>
@endpush