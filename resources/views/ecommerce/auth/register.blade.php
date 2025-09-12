@extends('auth.cartzilla-base')

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
        <!-- Registration form + Footer -->
        <div class="d-flex flex-column min-vh-100 w-100 py-4 mx-auto me-lg-5" style="max-width: 416px">

          <!-- Logo -->
<!--           <header class="navbar px-0 pb-4 mt-n2 mt-sm-0 mb-2 mb-md-3 mb-lg-4">
            <a href="{{ route('ecommerce.home') }}" class="navbar-brand pt-0">
              <img src="{{ asset('img/logo-black.png') }}" alt="Iravic Designs" class="img-fluid" style="max-width: 150px; height: auto;">
            </a>
          </header> -->

          <h1 class="h2 mt-auto">Crear una cuenta</h1>
          <div class="nav fs-sm mb-3 mb-lg-4">
            Ya tengo una cuenta
            <a class="nav-link text-decoration-underline p-0 ms-2" href="{{ route('customer.login.form') }}">Iniciar sesión</a>
          </div>
          <div class="nav fs-sm mb-4 d-lg-none">
            <span class="me-2">¿No estás seguro de crear una cuenta?</span>
            <a class="nav-link text-decoration-underline p-0" href="#benefits" data-bs-toggle="offcanvas" aria-controls="benefits">Explora los Beneficios</a>
          </div>

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
            
            <div class="position-relative mb-4">
              <label for="register-name" class="form-label">Nombre completo</label>
              <input type="text" class="form-control form-control-lg" id="register-name" name="name" value="{{ old('name') }}" required>
              <div class="invalid-tooltip bg-transparent py-0">¡Ingresa tu nombre completo!</div>
            </div>
            <div class="position-relative mb-4">
              <label for="register-email" class="form-label">Correo Electrónico</label>
              <input type="email" class="form-control form-control-lg" id="register-email" name="email" value="{{ old('email') }}" required>
              <div class="invalid-tooltip bg-transparent py-0">¡Ingresa una dirección de correo válida!</div>
            </div>
            <div class="mb-4">
              <label for="register-password" class="form-label">Contraseña</label>
              <div class="password-toggle">
                <input type="password" class="form-control form-control-lg" id="register-password" name="password" minlength="8" placeholder="Mínimo 8 caracteres" required>
                <div class="invalid-tooltip bg-transparent py-0">¡La contraseña no cumple con los criterios requeridos!</div>
                <label class="password-toggle-button fs-lg" aria-label="Mostrar/ocultar contraseña">
                  <input type="checkbox" class="btn-check">
                </label>
              </div>
            </div>
            <div class="mb-4">
              <label for="register-password-confirmation" class="form-label">Confirmar Contraseña</label>
              <div class="password-toggle">
                <input type="password" class="form-control form-control-lg" id="register-password-confirmation" name="password_confirmation" minlength="8" placeholder="Repite tu contraseña" required>
                <div class="invalid-tooltip bg-transparent py-0">¡Las contraseñas no coinciden!</div>
                <label class="password-toggle-button fs-lg" aria-label="Mostrar/ocultar contraseña">
                  <input type="checkbox" class="btn-check">
                </label>
              </div>
            </div>
            <div class="d-flex flex-column gap-2 mb-4">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="save-pass">
                <label for="save-pass" class="form-check-label">Guardar la contraseña</label>
              </div>
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="privacy" required>
                <label for="privacy" class="form-check-label">He leído y acepto la <a class="text-dark-emphasis" href="#!">Política de Privacidad</a></label>
              </div>
            </div>
            <button type="submit" class="btn btn-lg btn-primary w-100">
              Crear una cuenta
              <i class="ci-chevron-right fs-lg ms-1 me-n1"></i>
            </button>
          </form>

          <!-- Divider -->
          <div class="d-flex align-items-center my-4">
            <hr class="w-100 m-0">
            <span class="text-body-emphasis fw-medium text-nowrap mx-4">o registrarse con</span>
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
