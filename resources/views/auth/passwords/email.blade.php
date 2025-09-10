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

@push('css')
  <style>
    .reset-email-container {
      max-width: 440px;
      width: 100%;
    }
    .reset-email-card {
      border: none;
      box-shadow: 0 0.75rem 1.5rem rgba(18, 38, 63, 0.03);
      border-radius: 1rem;
    }
    .reset-email-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 2rem;
      border-radius: 1rem 1rem 0 0;
      text-align: center;
    }
    .reset-email-body {
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
    .btn-reset-email {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      border-radius: 0.5rem;
      padding: 0.75rem 2rem;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    .btn-reset-email:hover {
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
    .alert-success {
      background-color: #d4edda;
      border-color: #c3e6cb;
      color: #155724;
      border-radius: 0.5rem;
      padding: 1rem;
      margin-bottom: 1rem;
    }
  </style>
@endpush

@section('content')
    <div class="reset-email-container">
      <!-- Logo Section -->
      <div class="text-center mb-4">
        <img src="{{ asset('img/logo-black.png') }}" alt="Iravic Designs" class="img-fluid" style="max-width: 180px; height: auto;">
      </div>

      <!-- Reset Password Email Card -->
      <div class="card reset-email-card">
        <div class="reset-email-header">
          <h1 class="h4 mb-1">Recuperar Contraseña</h1>
          <p class="mb-0 opacity-75">Te enviaremos un enlace para restablecer tu contraseña</p>
        </div>
        <div class="reset-email-body">
          
          <!-- Success Message -->
          @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('status') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <form method="POST" action="{{ route('password.email') }}">
            @csrf
            
            <!-- Email Field -->
            <div class="mb-4">
              <label for="email" class="form-label">Correo Electrónico</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="ci-mail"></i>
                </span>
                <input class="form-control @error('email') is-invalid @enderror" type="email" id="email" placeholder="tu@email.com" name="email" value="{{ old('email') }}" required autofocus>
              </div>
              @error('email')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <!-- Send Reset Link Button -->
            <button class="btn btn-primary btn-reset-email w-100 mb-3" type="submit">
              <i class="ci-mail me-2"></i>Enviar Enlace de Recuperación
            </button>

            <!-- Back to Login Link -->
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
