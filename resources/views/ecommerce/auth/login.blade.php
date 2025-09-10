@extends('auth.cartzilla-base')

@section('title', 'Iniciar Sesión')
@section('meta-description', 'Inicia sesión en tu cuenta de Iravic para acceder a tus pedidos, favoritos y disfrutar de una experiencia de compra personalizada.')
@section('meta-keywords', 'login, iniciar sesión, cuenta cliente, acceso, tienda online')

@section('meta-tags')
<meta property="og:type" content="website">
<meta property="og:title" content="Iniciar Sesión | Iravic">
<meta property="og:description" content="Accede a tu cuenta en Iravic y gestiona tus pedidos de moda infantil.">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:site_name" content="Iravic">
<meta property="og:locale" content="es_ES">
@endsection

@section('content')
        <!-- Login form + Footer -->
        <div class="d-flex flex-column min-vh-100 w-100 py-4 mx-auto me-lg-5" style="max-width: 416px">

          <!-- Logo -->
          <header class="navbar px-0 pb-4 mt-n2 mt-sm-0 mb-2 mb-md-3 mb-lg-4">
            <a href="{{ route('ecommerce.home') }}" class="navbar-brand pt-0">
              <img src="{{ asset('img/logo-black.png') }}" alt="Iravic Designs" class="img-fluid" style="max-width: 150px; height: auto;">
            </a>
          </header>

          <h1 class="h2 mt-auto">Bienvenido</h1>
          <div class="nav fs-sm mb-4">
            ¿No tienes cuenta?
            <a class="nav-link text-decoration-underline p-0 ms-2" href="{{ route('customer.register.form') }}">Crear una cuenta</a>
          </div>

          <!-- Error Messages -->
          @if(Session::has('message'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              {{ Session::get('message') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <!-- Form -->
          <form class="needs-validation" method="POST" action="{{ route('customer.login') }}" novalidate>
            @csrf
            
            <div class="position-relative mb-4">
              <input type="email" class="form-control form-control-lg" placeholder="Correo Electrónico" name="email" value="{{ old('email') }}" required>
              <div class="invalid-tooltip bg-transparent py-0">¡Ingresa una dirección de correo válida!</div>
            </div>
            <div class="mb-4">
              <div class="password-toggle">
                <input type="password" class="form-control form-control-lg" placeholder="Contraseña" name="password" required>
                <div class="invalid-tooltip bg-transparent py-0">¡La contraseña es incorrecta!</div>
                <label class="password-toggle-button fs-lg" aria-label="Mostrar/ocultar contraseña">
                  <input type="checkbox" class="btn-check">
                </label>
              </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-4">
              <div class="form-check me-2">
                <input type="checkbox" class="form-check-input" id="remember-30" name="remember_me" value="1">
                <label for="remember-30" class="form-check-label">Recordar por 30 días</label>
              </div>
              <div class="nav">
                <a class="nav-link animate-underline p-0" href="{{ route('password.request') }}">
                  <span class="animate-target">¿Olvidaste tu contraseña?</span>
                </a>
              </div>
            </div>
            <button type="submit" class="btn btn-lg btn-primary w-100">Iniciar Sesión</button>
          </form>

          <!-- Divider -->
          <div class="d-flex align-items-center my-4">
            <hr class="w-100 m-0">
            <span class="text-body-emphasis fw-medium text-nowrap mx-4">o continuar con</span>
            <hr class="w-100 m-0">
          </div>

          <!-- Social login -->
          <div class="d-flex flex-column flex-sm-row gap-3 pb-4 mb-3 mb-lg-4">
            <button type="button" class="btn btn-lg btn-outline-secondary w-100 px-2">
              <i class="ci-google ms-1 me-1"></i>
              Google
            </button>
            <button type="button" class="btn btn-lg btn-outline-secondary w-100 px-2">
              <i class="ci-facebook ms-1 me-1"></i>
              Facebook
            </button>
            <button type="button" class="btn btn-lg btn-outline-secondary w-100 px-2">
              <i class="ci-apple ms-1 me-1"></i>
              Apple
            </button>
          </div>

          <!-- Footer -->
          <footer class="mt-auto">
            <div class="nav mb-4">
              <a class="nav-link text-decoration-underline p-0" href="#">¿Necesitas ayuda?</a>
            </div>
            <p class="fs-xs mb-0">
              &copy; Todos los derechos reservados. Hecho por <span class="animate-underline"><a class="animate-target text-dark-emphasis text-decoration-none" href="{{ route('ecommerce.home') }}" target="_blank" rel="noreferrer">Iravic Designs</a></span>
            </p>
          </footer>
        </div>
@endsection