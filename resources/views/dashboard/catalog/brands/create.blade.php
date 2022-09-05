@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.catalog.brands.create') }}</div>
                        <div class="card-body">
                          <form id="form-brands" method="POST" action="{{ route('marcas.store') }}">
                            @csrf
                            @include('dashboard.catalog.brands._form')
                            <a href="{{ route('marcas.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
                            <button class="btn btn-success" type="submit">{{ __('dashboard.form.create') }}</button>
                          </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
  @include('dashboard.catalog.brands.js.form')
@endpush