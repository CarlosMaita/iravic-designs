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

@push('css')
  <style>
    .register-container {
      max-width: 500px;
      width: 100%;
    }
    .register-card {
      border: none;
      box-shadow: 0 0.75rem 1.5rem rgba(18, 38, 63, 0.03);
      border-radius: 1rem;
    }
    .register-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 2rem;
      border-radius: 1rem 1rem 0 0;
      text-align: center;
    }
    .register-body {
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
    .btn-register {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      border-radius: 0.5rem;
      padding: 0.75rem 2rem;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    .btn-register:hover {
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
    .logo-section {
      margin-bottom: 2rem;
      text-align: center;
    }
    .logo-section img {
      max-width: 120px;
      height: auto;
    }
    .login-link {
      text-align: center;
      margin-top: 1rem;
      padding-top: 1rem;
      border-top: 1px solid #e1e5e9;
    }
  </style>
@endpush

@section('content')
    <div class="register-container">
      <!-- Logo Section -->
      <div class="logo-section">
        @isset($logoImg->value)
        <img class="img-fluid" src="{{ asset('storage/img/configs/'. $logoImg->value) }}" alt="Logo">
        @endisset
      </div>

      <!-- Registration Card -->
      <div class="card register-card">
        <div class="register-header">
          <h1 class="h4 mb-1">Crear Cuenta</h1>
          <p class="mb-0 opacity-75">Regístrate como nuevo cliente</p>
        </div>
        <div class="register-body">
          <form method="POST" action="{{ route('customer.register') }}">
            @csrf
            
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

            <!-- Name Field -->
            <div class="mb-3">
              <label for="name" class="form-label">Nombre y Apellido</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="ci-user"></i>
                </span>
                <input class="form-control" type="text" id="name" placeholder="Ingrese su nombre completo" name="name" value="{{ old('name') }}" required autofocus>
              </div>
            </div>

            <!-- Email Field -->
            <div class="mb-3">
              <label for="email" class="form-label">Correo Electrónico</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="ci-mail"></i>
                </span>
                <input class="form-control" type="email" id="email" placeholder="ejemplo@correo.com" name="email" value="{{ old('email') }}" required>
              </div>
            </div>

            <!-- Password Field -->
            <div class="mb-3">
              <label for="password" class="form-label">Contraseña</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="ci-locked"></i>
                </span>
                <input class="form-control" type="password" id="password" placeholder="Mínimo 8 caracteres" name="password" required>
              </div>
            </div>

            <!-- Confirm Password Field -->
            <div class="mb-4">
              <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="ci-locked"></i>
                </span>
                <input class="form-control" type="password" id="password_confirmation" placeholder="Repita su contraseña" name="password_confirmation" required>
              </div>
            </div>

            <!-- Register Button -->
            <button class="btn btn-primary btn-register w-100 mb-3" type="submit">
              <i class="ci-user-plus me-2"></i>Crear Cuenta
            </button>

            <!-- Login Link -->
            <div class="login-link">
              <p class="mb-0">¿Ya tienes cuenta? 
                <a href="{{ route('customer.login.form') }}" class="text-decoration-none">
                  Inicia Sesión
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