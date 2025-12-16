@extends('layouts.minimalist-auth')

@section('title', __('auth.login'))

@section('content')
  <!-- Brand -->
  <div class="text-center mb-4">
    <h1 class="h4 fw-bold mb-1">{{ config('app.name') }}</h1>
    <p class="text-muted mb-0">Panel de Administración</p>
  </div>

  <!-- Heading -->
  <h2 class="h5 text-center mb-4">{{ __('auth.login') }}</h2>

  <!-- Error Messages -->
  @if(Session::has('message'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ Session::get('message') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Form -->
  <form method="POST" action="{{ route('login') }}">
    @csrf

    <!-- Email Field -->
    <div class="mb-3">
      <label for="email" class="form-label">{{ __('E-Mail Address') }}</label>
      <input class="form-control" type="email" id="email" placeholder="{{ __('E-Mail Address') }}" name="email" value="{{ old('email') }}" required autofocus>
    </div>

    <!-- Password Field -->
    <div class="mb-3">
      <label for="password" class="form-label">{{ __('Password') }}</label>
      <input class="form-control" type="password" id="password" placeholder="{{ __('Password') }}" name="password" required>
    </div>

    <!-- Remember Me Checkbox -->
    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" value="1" id="remember_me" name="remember_me">
      <label class="form-check-label" for="remember_me">
        {{ __('Remember Me') }}
      </label>
    </div>

    <!-- Login Button -->
    <button class="btn btn-primary w-100 mb-3" type="submit">
      {{ __('Login') }}
    </button>

    <!-- Forgot Password Link -->
    <div class="text-center">
      <a href="{{ route('password.request') }}" class="text-decoration-none small">
        {{ __('Forgot Your Password?') }}
      </a>
    </div>
  </form>

  <!-- Footer -->
  <p class="text-center small text-muted mt-4 mb-0">
    &copy; {{ date('Y') }} {{ config('app.name') }}
  </p>
@endsection