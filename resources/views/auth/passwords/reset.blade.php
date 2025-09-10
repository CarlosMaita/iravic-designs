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

@push('css')
  <style>
    .reset-container {
      max-width: 440px;
      width: 100%;
    }
    .reset-card {
      border: none;
      box-shadow: 0 0.75rem 1.5rem rgba(18, 38, 63, 0.03);
      border-radius: 1rem;
    }
    .reset-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 2rem;
      border-radius: 1rem 1rem 0 0;
      text-align: center;
    }
    .reset-body {
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
    .btn-reset {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      border-radius: 0.5rem;
      padding: 0.75rem 2rem;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    .btn-reset:hover {
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
  </style>
@endpush

@section('content')
    <div class="reset-container">
      <!-- Logo Section -->
      <div class="text-center mb-4">
        <img src="{{ asset('img/logo-black.png') }}" alt="Iravic Designs" class="img-fluid" style="max-width: 180px; height: auto;">
      </div>

      <!-- Reset Password Card -->
      <div class="card reset-card">
        <div class="reset-header">
          <h1 class="h4 mb-1">Restablecer Contraseña</h1>
          <p class="mb-0 opacity-75">Crea una nueva contraseña para tu cuenta</p>
        </div>
        <div class="reset-body">
          <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
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

            <!-- Email Field -->
            <div class="mb-3">
              <label for="email" class="form-label">Correo Electrónico</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="ci-mail"></i>
                </span>
                <input class="form-control @error('email') is-invalid @enderror" type="email" id="email" placeholder="tu@email.com" name="email" value="{{ $email ?? old('email') }}" required autofocus readonly>
              </div>
              @error('email')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <!-- Password Field -->
            <div class="mb-3">
              <label for="password" class="form-label">Nueva Contraseña</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="ci-locked"></i>
                </span>
                <input class="form-control @error('password') is-invalid @enderror" type="password" id="password" placeholder="Mínimo 8 caracteres" name="password" required>
              </div>
              @error('password')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <!-- Confirm Password Field -->
            <div class="mb-4">
              <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="ci-locked"></i>
                </span>
                <input class="form-control" type="password" id="password_confirmation" placeholder="Repita su nueva contraseña" name="password_confirmation" required>
              </div>
            </div>

            <!-- Reset Button -->
            <button class="btn btn-primary btn-reset w-100 mb-3" type="submit">
              <i class="ci-refresh-cw me-2"></i>Restablecer Contraseña
            </button>

            <!-- Login Link -->
            <div class="text-center">
              <p class="mb-0">¿Recordaste tu contraseña? 
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
