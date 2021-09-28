@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
      <div class="animated fadeIn">
          <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                  <div class="card">
                      <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.orders.edit') }} - #{{ $order->id }}</div>
                      <div class="card-body">
                        <form id="form-orders" method="POST" action="{{ route('ventas.update', [$order->id]) }}">
                          @csrf
                          @method('PUT')
                          @include('dashboard.orders._form')
                          <button class="btn btn-success" type="submit">{{ __('dashboard.form.update') }}</button>
                          <a href="{{ route('ventas.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
                        </form>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  @include('dashboard.orders._modal_new_costumer', ['customer' => new App\Models\Customer])
@endsection

@push('js')
  <script>
    const $customer = null;
    const is_creating_order = true;
  </script>
  <script src="{{ asset('plugins/underscore/underscore.js') }}"></script>

  @include('plugins.google-maps')
  @include('plugins.select2')
  @include('plugins.sweetalert')
  @include('dashboard.customers.js.customer-map')
  @include('dashboard.customers.js.form')
  @include('dashboard.orders.js.form')
@endpush