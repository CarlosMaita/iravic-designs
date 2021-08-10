@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.customers.create') }}</div>
                        <div class="card-body">
                          <form id="form-customers" method="POST" action="{{ route('clientes.store') }}" enctype="multipart/form-data">
                            @csrf
                            @include('dashboard.customers._form')
                            <button class="btn btn-success" type="submit">{{ __('dashboard.form.create') }}</button>
                            <a href="{{ route('clientes.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
                          </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
  <script>
    const $customer = @json($customer);
    const is_creating_order = false;
  </script>
  <script src="{{ asset('plugins/underscore/underscore.js') }}"></script>

  @include('plugins.google-maps')
  @include('plugins.select2')
  @include('plugins.sweetalert')
  @include('dashboard.customers.js.customer-map')
  @include('dashboard.customers.js.form')
@endpush