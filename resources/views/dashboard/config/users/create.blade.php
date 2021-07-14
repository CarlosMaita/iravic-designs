@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.config.users.create') }}</div>
                        <div class="card-body">
                          <form id="form-users" method="POST" action="{{ route('usuarios.store') }}">
                            @csrf
                            @include('dashboard.config.users._form')
                            <button class="btn btn-success" type="submit">{{ __('dashboard.form.create') }}</button>
                            <a href="{{ route('usuarios.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
                          </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
  @include('plugins.select2')
  @include('dashboard.config.users.js.form')
@endpush