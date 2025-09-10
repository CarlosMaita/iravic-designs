@extends('auth.cartzilla-base')

@section('title', 'Recuperar Contraseña')
@section('meta-description', 'Recupera el acceso a tu cuenta en Iravic. Ingresa tu correo electrónico para recibir un enlace de restablecimiento de contraseña.')
@section('meta-keywords', 'recuperar contraseña, olvidé contraseña, restablecer acceso, email recuperación')

@section('meta-tags')
<meta property="og:type" content="website">
<meta property="og:title" content="Recuperar Contraseña | Iravic">
<meta property="og:description" content="Recupera el acceso a tu cuenta en Iravic con nuestro sistema de restablecimiento de contraseña.">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:site_name" content="Iravic">
<meta property="og:locale" content="es_ES">
@endsection

@section('content')
        <!-- Login form + Footer -->
        <div class="d-flex flex-column min-vh-100 w-100 py-4 mx-auto me-lg-5" style="max-width: 416px">

          <!-- Logo -->
          <header class="navbar align-items-center px-0 pb-4 mt-n2 mt-sm-0 mb-2 mb-md-3 mb-lg-4">
            <a href="{{ route('ecommerce.home') }}" class="navbar-brand pt-0">
              <img src="{{ asset('img/logo-black.png') }}" alt="Iravic Designs" class="img-fluid" style="max-width: 150px; height: auto;">
            </a>
            <div class="nav">
              <a class="nav-link fs-base animate-underline p-0" href="{{ route('customer.login.form') }}">
                <i class="ci-chevron-left fs-lg ms-n1 me-1"></i>
                <span class="animate-target">Volver a Iniciar Sesión</span>
              </a>
            </div>
          </header>

          <h1 class="h2 mt-auto">¿Olvidaste tu contraseña?</h1>
          <p class="pb-2 pb-md-3">Ingresa la dirección de correo que usaste cuando te registraste y te enviaremos instrucciones para restablecer tu contraseña</p>

          <!-- Success Message -->
          @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('status') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <!-- Form -->
          <form class="needs-validation pb-4 mb-3 mb-lg-4" method="POST" action="{{ route('password.email') }}" novalidate>
            @csrf
            
            <div class="position-relative mb-4">
              <i class="ci-mail position-absolute top-50 start-0 translate-middle-y fs-lg ms-3"></i>
              <input type="email" class="form-control form-control-lg form-icon-start @error('email') is-invalid @enderror" placeholder="Dirección de correo electrónico" name="email" value="{{ old('email') }}" required>
              <div class="invalid-tooltip bg-transparent py-0">¡Por favor ingresa una dirección de correo válida!</div>
              @error('email')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
            <button type="submit" class="btn btn-lg btn-primary w-100">Restablecer contraseña</button>
          </form>

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
