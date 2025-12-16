@extends('layouts.minimalist-auth')

@section('title', 'Crear Cuenta')
@section('meta-description', 'Regístrate en Iravic y únete a nuestra comunidad. Crea tu cuenta para acceder a ofertas exclusivas y gestionar tus compras de moda infantil.')
@section('meta-keywords', 'registro, crear cuenta, nueva cuenta, registro cliente, tienda online')

@section('meta-tags')
<meta property="og:type" content="website">
<meta property="og:title" content="Crear Cuenta | Iravic">
<meta property="og:description" content="Regístrate en Iravic y disfruta de ofertas exclusivas en moda infantil.">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:site_name" content="Iravic">
<meta property="og:locale" content="es_ES">
@endsection

@section('content')
  <!-- Brand / Back home -->
  <div class="text-center mb-4">
    <a href="{{ route('ecommerce.home') }}" class="d-inline-flex align-items-center gap-2 text-decoration-none text-dark">
      <img src="{{ asset('img/logo-black.png') }}" alt="Iravic Designs" style="max-width: 40px; height: auto;">
      <span class="fs-5 fw-semibold">Iravic</span>
    </a>
  </div>

  <!-- Heading -->
  <h1 class="h3 text-center mb-2">Crear una cuenta</h1>
  <p class="text-center text-muted mb-4">Únete a Iravic Designs</p>

  <!-- Error Messages -->
  @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Form -->
  <form class="needs-validation" method="POST" action="{{ route('customer.register') }}" novalidate>
    @csrf
    
    <div class="mb-3">
      <label for="register-name" class="form-label">Nombre completo</label>
      <input type="text" class="form-control" id="register-name" name="name" value="{{ old('name') }}" required>
      <div class="invalid-feedback">Ingresa tu nombre completo</div>
    </div>

    <div class="mb-3">
      <label for="register-email" class="form-label">Correo Electrónico</label>
      <input type="email" class="form-control" id="register-email" name="email" value="{{ old('email') }}" required>
      <div class="invalid-feedback">Ingresa una dirección de correo válida</div>
    </div>

    <div class="mb-3">
      <label for="register-password" class="form-label">Contraseña</label>
      <div class="password-toggle">
        <input type="password" class="form-control" id="register-password" name="password" minlength="8" placeholder="Mínimo 8 caracteres" required>
        <div class="invalid-feedback">La contraseña debe tener al menos 8 caracteres</div>
        <label class="password-toggle-button fs-lg" aria-label="Mostrar/ocultar contraseña">
          <input type="checkbox" class="btn-check">
        </label>
      </div>
    </div>

    <div class="mb-3">
      <label for="register-password-confirmation" class="form-label">Confirmar Contraseña</label>
      <div class="password-toggle">
        <input type="password" class="form-control" id="register-password-confirmation" name="password_confirmation" minlength="8" placeholder="Repite tu contraseña" required>
        <div class="invalid-feedback">Las contraseñas no coinciden</div>
        <label class="password-toggle-button fs-lg" aria-label="Mostrar/ocultar contraseña">
          <input type="checkbox" class="btn-check">
        </label>
      </div>
    </div>

    <div class="form-check mb-3">
      <input type="checkbox" class="form-check-input" id="privacy" required>
      <label for="privacy" class="form-check-label small">He leído y acepto la <a href="#!">Política de Privacidad</a></label>
    </div>

    <button type="submit" class="btn btn-dark w-100 mb-3">
      Crear una cuenta
    </button>
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
    <span>Registrarse con Google</span>
  </a>

  <!-- Login link -->
  <p class="text-center mb-3">
    ¿Ya tienes cuenta? 
    <a href="{{ route('customer.login.form') }}" class="text-decoration-none">Iniciar sesión</a>
  </p>

  <!-- Footer -->
  <p class="text-center small text-muted mt-4">
    &copy; {{ date('Y') }} Iravic Designs. Todos los derechos reservados.
  </p>
@endsection
