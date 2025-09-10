@extends('auth.cartzilla-base')

@section('title', 'Restablecer Contraseña')
@section('meta-description', 'Restablece tu contraseña en Iravic para recuperar el acceso a tu cuenta y seguir disfrutando de nuestros productos.')
@section('meta-keywords', 'restablecer contraseña, recuperar cuenta, nueva contraseña, acceso cuenta')

@section('meta-tags')
<meta property="og:type" content="website">
<meta property="og:title" content="Restablecer Contraseña | Iravic">
<meta property="og:description" content="Restablece tu contraseña en Iravic y recupera el acceso a tu cuenta.">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:site_name" content="Iravic">
<meta property="og:locale" content="es_ES">
@endsection

@section('content')
        <!-- Password Reset form + Footer -->
        <div class="d-flex flex-column min-vh-100 w-100 py-4 mx-auto me-lg-5" style="max-width: 416px">

          <!-- Logo -->
          <header class="navbar align-items-center px-0 pb-4 mt-n2 mt-sm-0 mb-2 mb-md-3 mb-lg-4">
            <a href="{{ route('ecommerce.home') }}" class="navbar-brand pt-0">
              <h6 class="m-0 text-uppercase" style="font-family: Roboto, sans-serif; letter-spacing: 3px;">Iravic Designs</h6>
            </a>
            <div class="nav">
              <a class="nav-link fs-base animate-underline p-0" href="{{ route('customer.login.form') }}">
                <i class="ci-chevron-left fs-lg ms-n1 me-1"></i>
                <span class="animate-target">Volver a Iniciar Sesión</span>
              </a>
            </div>
          </header>

          <h1 class="h2 mt-auto">Restablecer Contraseña</h1>
          <p class="pb-2 pb-md-3">Crea una nueva contraseña para tu cuenta</p>

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
          <form class="needs-validation pb-4 mb-3 mb-lg-4" method="POST" action="{{ route('password.update') }}" novalidate>
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="position-relative mb-4">
              <label for="reset-email" class="form-label">Correo Electrónico</label>
              <i class="ci-mail position-absolute top-50 start-0 translate-middle-y fs-lg ms-3" style="margin-top: 12px;"></i>
              <input type="email" class="form-control form-control-lg form-icon-start @error('email') is-invalid @enderror" id="reset-email" name="email" value="{{ $email ?? old('email') }}" required readonly>
              @error('email')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
            
            <div class="mb-4">
              <label for="reset-password" class="form-label">Nueva Contraseña</label>
              <div class="password-toggle">
                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="reset-password" name="password" minlength="8" placeholder="Mínimo 8 caracteres" required>
                <div class="invalid-tooltip bg-transparent py-0">¡La contraseña no cumple con los criterios requeridos!</div>
                <label class="password-toggle-button fs-lg" aria-label="Mostrar/ocultar contraseña">
                  <input type="checkbox" class="btn-check">
                </label>
              </div>
              @error('password')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
            
            <div class="mb-4">
              <label for="reset-password-confirmation" class="form-label">Confirmar Nueva Contraseña</label>
              <div class="password-toggle">
                <input type="password" class="form-control form-control-lg" id="reset-password-confirmation" name="password_confirmation" minlength="8" placeholder="Repita su nueva contraseña" required>
                <div class="invalid-tooltip bg-transparent py-0">¡Las contraseñas no coinciden!</div>
                <label class="password-toggle-button fs-lg" aria-label="Mostrar/ocultar contraseña">
                  <input type="checkbox" class="btn-check">
                </label>
              </div>
            </div>
            
            <button type="submit" class="btn btn-lg btn-primary w-100">
              <i class="ci-refresh-cw me-2"></i>Restablecer Contraseña
            </button>
          </form>

          <!-- Footer -->
          <footer class="mt-auto">
            <div class="nav mb-4">
              <p class="mb-0">¿Recordaste tu contraseña? 
                <a class="nav-link text-decoration-underline p-0 d-inline" href="{{ route('customer.login.form') }}">
                  Inicia Sesión
                </a>
              </p>
            </div>
            <p class="fs-xs mb-0">
              &copy; Todos los derechos reservados. Hecho por <span class="animate-underline"><a class="animate-target text-dark-emphasis text-decoration-none" href="{{ route('ecommerce.home') }}" target="_blank" rel="noreferrer">Iravic Designs</a></span>
            </p>
          </footer>
        </div>
@endsection
