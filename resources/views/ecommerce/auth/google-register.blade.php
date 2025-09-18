@extends('auth.cartzilla-base')

@section('title', 'Completar Registro con Google')
@section('meta-description', 'Completa tu registro en Iravic después de autenticarte con Google.')
@section('meta-keywords', 'registro google, completar registro, nueva cuenta, tienda online')

@section('meta-tags')
<meta property="og:type" content="website">
<meta property="og:title" content="Completar Registro | Iravic">
<meta property="og:description" content="Completa tu registro en Iravic después de autenticarte con Google.">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:site_name" content="Iravic">
<meta property="og:locale" content="es_ES">
@endsection

@section('content')
        <!-- Registration form + Footer -->
        <div class="d-flex flex-column min-vh-100 w-100 py-4 mx-auto me-lg-5" style="max-width: 416px">

          <h1 class="h2 mt-auto">Completar tu registro</h1>
          <div class="nav fs-sm mb-3 mb-lg-4">
            Autenticado con Google como: <strong>{{ $googleUser['email'] }}</strong>
          </div>
          <div class="nav fs-sm mb-4 d-lg-none">
            <span class="me-2">¿Ya tienes una cuenta?</span>
            <a class="nav-link text-decoration-underline p-0" href="{{ route('customer.login.form') }}">Iniciar sesión</a>
          </div>

          <!-- Error/Success Messages -->
          @if(Session::has('message'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
              {{ Session::get('message') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          @if(Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              {{ Session::get('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <!-- Form -->
          <form class="needs-validation" method="POST" action="{{ route('customer.google.register.complete') }}" novalidate>
            @csrf
            
            <div class="position-relative mb-4">
              <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                     placeholder="Nombre Completo" name="name" value="{{ old('name', $googleUser['name']) }}" required>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @else
                <div class="invalid-tooltip bg-transparent py-0">¡Ingresa tu nombre completo!</div>
              @enderror
            </div>
            
            <div class="mb-4">
              <div class="password-toggle">
                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                       placeholder="Contraseña" name="password" required>
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @else
                  <div class="invalid-tooltip bg-transparent py-0">¡Ingresa una contraseña!</div>
                @enderror
                <label class="password-toggle-button fs-lg" aria-label="Mostrar/ocultar contraseña">
                  <input type="checkbox" class="btn-check">
                </label>
              </div>
            </div>
            
            <div class="mb-4">
              <div class="password-toggle">
                <input type="password" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" 
                       placeholder="Confirmar Contraseña" name="password_confirmation" required>
                @error('password_confirmation')
                  <div class="invalid-feedback">{{ $message }}</div>
                @else
                  <div class="invalid-tooltip bg-transparent py-0">¡Confirma tu contraseña!</div>
                @enderror
                <label class="password-toggle-button fs-lg" aria-label="Mostrar/ocultar contraseña">
                  <input type="checkbox" class="btn-check">
                </label>
              </div>
            </div>
            
            <button type="submit" class="btn btn-lg btn-primary w-100">Completar Registro</button>
          </form>

          <!-- Divider -->
          <div class="d-flex align-items-center my-4">
            <hr class="w-100 m-0">
            <span class="text-body-emphasis fw-medium text-nowrap mx-4">o</span>
            <hr class="w-100 m-0">
          </div>

          <!-- Back to login -->
          <div class="text-center">
            <a href="{{ route('customer.login.form') }}" class="btn btn-outline-secondary w-100">
              Volver al inicio de sesión
            </a>
          </div>

          <!-- Footer -->
          <footer class="mt-auto">
            <div class="nav mb-4">
              <a class="nav-link text-decoration-underline p-0" href="#">¿Necesitas ayuda?</a>
            </div>
            <p class="fs-xs mb-0">
              Al continuar, aceptas nuestros 
              <a class="nav-link d-inline fs-xs p-0 ms-1" href="#">Términos y condiciones</a>
              y 
              <a class="nav-link d-inline fs-xs p-0 ms-1" href="#">Política de privacidad</a>
            </p>
          </footer>

        </div>
@endsection