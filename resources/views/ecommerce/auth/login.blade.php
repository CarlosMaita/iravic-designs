@extends('layouts.minimalist-auth')

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
  <!-- Brand / Back home -->
  <div class="text-center mb-4">
    <a href="{{ route('ecommerce.home') }}" class="d-inline-flex align-items-center text-decoration-none text-dark">
      <h6 class="m-0 text-uppercase" style="font-family: Roboto, sans-serif; letter-spacing: 3px;">Iravic Designs</h6>
    </a>
  </div>

  <!-- Heading -->
  <h1 class="h3 text-center mb-2">Bienvenido</h1>
  <p class="text-center text-muted mb-4">Inicia sesión para continuar</p>

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

    <div class="mb-3">
      <label for="email" class="form-label">Correo electrónico</label>
      <input id="email" type="email" class="form-control" placeholder="tu@ejemplo.com" name="email" value="{{ old('email') }}" autocomplete="email" required>
      <div class="invalid-feedback">Ingresa una dirección de correo válida</div>
    </div>

    <div class="mb-3">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <label for="password" class="form-label mb-0">Contraseña</label>
        <a class="small text-decoration-none" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
      </div>
      <div class="password-toggle">
        <input id="password" type="password" class="form-control" placeholder="••••••••" name="password" autocomplete="current-password" required>
        <div class="invalid-feedback">La contraseña es requerida</div>
        <label class="password-toggle-button fs-lg" aria-label="Mostrar/ocultar contraseña">
          <input type="checkbox" class="btn-check">
        </label>
      </div>
    </div>

    <div class="form-check mb-3">
      <input type="checkbox" class="form-check-input" id="remember-30" name="remember" value="1">
      <label for="remember-30" class="form-check-label">Recordarme por 30 días</label>
    </div>

    <button type="submit" class="btn btn-dark w-100 mb-3">Iniciar Sesión</button>
  </form>

  <!-- Divider -->
  <div class="d-flex align-items-center my-3">
    <hr class="w-100 m-0">
    <span class="text-muted small mx-3">o</span>
    <hr class="w-100 m-0">
  </div>

  <!-- Social login -->
  <a href="{{ route('customer.google.redirect') }}" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2 mb-3">
    <i class="ci-google"></i>
    <span>Continuar con Google</span>
  </a>

  <!-- Register link -->
  <p class="text-center mb-3">
    ¿No tienes cuenta? 
    <a href="{{ route('customer.register.form') }}" class="text-decoration-none">Crear una cuenta</a>
  </p>

  <!-- Footer -->
  <p class="text-center small text-muted mt-4">
    &copy; {{ date('Y') }} Iravic Designs. Todos los derechos reservados.
  </p>
@endsection
