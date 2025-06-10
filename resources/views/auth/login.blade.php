@extends('auth.base')

@push('css')
  <style>
    @media only screen and (max-width: 500px) {
      h1 {
        font-size: 16px;
      }
    }
    @media only screen and (min-height: 670px) {
      #form-wrap {
        margin-bottom: 150px;
      }
    }
  </style>
@endpush

@section('content')
    <div class="container">
      <div class="row justify-content-center">
        <div id="form-wrap" class="col-md-8">
          <div class="card-group">
            <div class="card p-2">
              <div class="card-body">
                <h1>{{ __('auth.login') }}</h1>
                <form method="POST" action="{{ route('login') }}">
                  @csrf
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <svg class="c-icon">
                          <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-user"></use>
                        </svg>
                      </span>
                    </div>
                    <input class="form-control" type="text" placeholder="{{ __('E-Mail Address') }}" name="email" value="{{ old('email') }}" required autofocus>
                  </div>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <svg class="c-icon">
                          <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-lock-locked"></use>
                        </svg>
                      </span>
                    </div>
                    <input class="form-control" type="password" placeholder="{{ __('Password') }}" name="password" required>
                  </div>
                  <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" value="1" id="remember_me" name="remember_me" value='1'>
                    <label class="form-check-label" for="remember_me">
                      {{ __('Remember Me') }}
                    </label>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                        @if(Session::has('message'))
                            <p class="alert alert-danger">{{ Session::get('message') }}</p>
                        @endif
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-6">
                        <button class="btn btn-primary px-4" type="submit">{{ __('Login') }}</button>
                    </div>
                  </div>
                </form>
                <div class="col-12 text-right">
                    <a href="{{ route('password.request') }}" class="btn btn-link px-0">{{ __('Forgot Your Password?') }}</a>
                </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection