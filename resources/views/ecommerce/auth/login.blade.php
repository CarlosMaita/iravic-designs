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

@push('css')
  <style>
    .login-container {
      max-width: 440px;
      width: 100%;
    }
    .login-card {
      border: none;
      box-shadow: 0 0.75rem 1.5rem rgba(18, 38, 63, 0.03);
      border-radius: 1rem;
    }
    .login-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 2rem;
      border-radius: 1rem 1rem 0 0;
      text-align: center;
    }
    .login-body {
      padding: 2rem;
    }
    .form-control {
      border-radius: 0.5rem;
      border: 1px solid #e1e5e9;
      padding: 0.75rem 1rem;
      font-size: 0.875rem;
    }
    .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .btn-login {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      border-radius: 0.5rem;
      padding: 0.75rem 2rem;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    .btn-login:hover {
      transform: translateY(-1px);
      box-shadow: 0 0.5rem 1rem rgba(102, 126, 234, 0.3);
    }
    .input-group-text {
      background: transparent;
      border: 1px solid #e1e5e9;
      border-right: none;
      border-radius: 0.5rem 0 0 0.5rem;
      color: #6c757d;
    }
    .input-group .form-control {
      border-left: none;
      border-radius: 0 0.5rem 0.5rem 0;
    }
    .form-check-input:checked {
      background-color: #667eea;
      border-color: #667eea;
    }
  </style>
@endpush

@section('content')
    <div class="login-container">
      <!-- Logo Section -->
      <div class="text-center mb-4">
        <img src="{{ asset('img/logo-black.png') }}" alt="Iravic Designs" class="img-fluid" style="max-width: 180px; height: auto;">
      </div>

      <!-- Login Card -->
      <div class="card login-card">
        <div class="login-header">
          <h1 class="h4 mb-1">Bienvenido</h1>
          <p class="mb-0 opacity-75">Ingresa a tu cuenta de cliente</p>
        </div>
        <div class="login-body">
          <form method="POST" action="{{ route('customer.login') }}">
            @csrf
            
            <!-- Error Messages -->
            @if(Session::has('message'))
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ Session::get('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            <!-- Email Field -->
            <div class="mb-3">
              <label for="email" class="form-label">Correo Electrónico</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="ci-user"></i>
                </span>
                <input class="form-control" type="email" id="email" placeholder="{{ __('tu@email.com') }}" name="email" value="{{ old('email') }}" required autofocus>
              </div>
            </div>

            <!-- Password Field -->
            <div class="mb-3">
              <label for="password" class="form-label">Contraseña</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="ci-locked"></i>
                </span>
                <input class="form-control" type="password" id="password" placeholder="{{ __('Password') }}" name="password" required>
              </div>
            </div>

            <!-- Remember Me Checkbox -->
            <div class="form-check mb-4">
              <input class="form-check-input" type="checkbox" value="1" id="remember_me" name="remember_me">
              <label class="form-check-label" for="remember_me">
                {{ __('Remember Me') }}
              </label>
            </div>

            <!-- Login Button -->
            <button class="btn btn-primary btn-login w-100 mb-3" type="submit">
              <i class="ci-sign-in me-2"></i>{{ __('Login') }}
            </button>

            <!-- Forgot Password Link -->
            <div class="text-center mb-2">
              <a href="{{ route('password.request') }}" class="text-decoration-none">
                {{ __('Forgot Your Password?') }}
              </a>
            </div>

            <!-- Registration Link -->
            <div class="text-center">
              <p class="mb-0">¿No tienes cuenta? 
                <a href="{{ route('customer.register.form') }}" class="text-decoration-none fw-bold">
                  Regístrate
                </a>
              </p>
            </div>
          </form>
        </div>
      </div>
    </div>
@endsection

@push('js')

@endpush