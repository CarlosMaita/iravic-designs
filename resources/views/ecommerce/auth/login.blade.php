@extends('auth.cartzilla-base')

@section('title', 'Iniciar Sesión')
@section('meta-description', 'Inicia sesión en tu cuenta de Iravic para acceder a tus pedidos, favoritos y disfrutar de una experiencia de compra personalizada.')
@section('meta-keywords', 'login, iniciar sesión, cuenta cliente, acceso, tienda online')

@push('css')
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('meta-tags')
<meta property="og:type" content="website">
<meta property="og:title" content="Iniciar Sesión | Iravic">
<meta property="og:description" content="Accede a tu cuenta en Iravic y gestiona tus pedidos de moda infantil.">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:site_name" content="Iravic">
<meta property="og:locale" content="es_ES">
@endsection

@section('content')
  <!-- Left panel: Login -->
  <div class="auth-panel d-flex flex-column min-vh-100 w-100 py-4 mx-auto me-lg-5" style="max-width: 480px">

    <!-- Brand / Back home -->
    <header class="auth-brand navbar px-0 pb-4 mt-n2 mt-sm-0 mb-2 mb-md-3 mb-lg-4">
      <a href="{{ route('ecommerce.home') }}" class="navbar-brand pt-0 d-flex align-items-center gap-2 text-decoration-none">
        <img src="{{ asset('img/logo-black.png') }}" alt="Iravic Designs" class="img-fluid" style="max-width: 36px; height: auto;">
        <span class="brand-text fw-semibold">Iravic</span>
      </a>
    </header>

    <!-- Heading -->
  <h1 class="display-6 fw-semibold mb-2">Bienvenido</h1>
  <p class="text-body-secondary mb-4">Inicia sesión para continuar</p>

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

      <div class="position-relative mb-3">
        <label for="email" class="form-label small text-body-secondary">Correo electrónico</label>
        <input id="email" type="email" class="form-control form-control-lg auth-input" placeholder="tu@ejemplo.com" name="email" value="{{ old('email') }}" autocomplete="email" required>
        <div class="invalid-tooltip bg-transparent py-0">¡Ingresa una dirección de correo válida!</div>
      </div>

      <div class="mb-3">
        <div class="d-flex align-items-center justify-content-between">
          <label for="password" class="form-label small text-body-secondary mb-1">Contraseña</label>
          <a class="small text-decoration-underline" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
        </div>
        <div class="password-toggle">
          <input id="password" type="password" class="form-control form-control-lg auth-input" placeholder="••••••••" name="password" autocomplete="current-password" required>
          <div class="invalid-tooltip bg-transparent py-0">¡La contraseña es incorrecta!</div>
          <label class="password-toggle-button fs-lg" aria-label="Mostrar/ocultar contraseña">
            <input type="checkbox" class="btn-check">
          </label>
        </div>
      </div>

      <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="form-check me-2">
          <input type="checkbox" class="form-check-input" id="remember-30" name="remember" value="1">
          <label for="remember-30" class="form-check-label">Recordarme por 30 días</label>
        </div>
      </div>

      <button type="submit" class="btn btn-lg btn-dark w-100 rounded-3">Iniciar Sesión</button>
    </form>

    <!-- Divider -->
    <div class="d-flex align-items-center my-3">
      <hr class="w-100 m-0">
      <span class="text-body-emphasis fw-medium text-nowrap mx-4">o continuar con</span>
      <hr class="w-100 m-0">
    </div>

    <!-- Social login -->
    <div class="d-grid mb-3">
      <a href="{{ route('customer.google.redirect') }}" class="btn btn-lg btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2 auth-google-btn bg-white border rounded-3">
        <i class="ci-google"></i>
        <span>Continuar con Google</span>
      </a>
    </div>

    <div class="text-center mt-3">
      <small class="text-body-secondary">Al continuar, aceptas nuestros <a href="#" class="text-decoration-underline">Términos de Servicio</a> y <a href="#" class="text-decoration-underline">Política de Privacidad</a></small>
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

@section('cover')
  <!-- Custom right cover with girl image from Figma -->
  <div class="d-none d-lg-block w-100 py-4 ms-auto" style="max-width: 1034px">
    <div class="d-flex flex-column justify-content-end h-100 rounded-5 overflow-hidden position-relative auth-cover">
      <div class="ratio position-relative z-2" style="--cz-aspect-ratio: calc(1030 / 1032 * 100%)">
        @php
          $webp = public_path('img/login/girl.webp');
          $jpg  = public_path('img/login/girl.jpg');
          $coverUrl = file_exists($webp)
            ? asset('img/login/girl.webp')
            : (file_exists($jpg) ? asset('img/login/girl.jpg') : asset('assets/cartzilla/img/account/cover.png'));
        @endphp
        <img src="{{ $coverUrl }}" alt="Moda infantil" class="object-fit-cover w-100 h-100">
      </div>
      <!-- Gradient overlay -->
      <span class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(0deg, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0) 30%)"></span>
    </div>
  </div>
@endsection
