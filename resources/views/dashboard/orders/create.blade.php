@extends('dashboard.base')

@push('css')
  <style>
    .input-group .select2-container{
      width: calc(100% - 43px)!important;
    }
    .input-group .select2-container .select2-selection--single {
      border-radius: 4px 0px 0px 4px;
    }
  </style>
@endpush

@section('content')
  <div class="container-fluid">
      <div class="animated fadeIn">
          <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                  <div class="card">
                      <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.boxes-sales.orders.create') }}</div>
                      <div class="card-body px-2">
                        <form id="form-orders" method="POST" action="{{ route('pedidos.store') }}">
                          @csrf
                          @include('dashboard.orders._form')
                          <hr>
                          <div class="container-fluid">
                            <div class="row">
                              <div class="col-md-12 justify-content-end">
                                {{-- <button class="btn btn-success" type="submit">{{ __('dashboard.form.create') }}</button> --}}
                                <a href="{{ route('pedidos.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
                              </div>
                            </div>
                          </div>
                        </form>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  @include('dashboard.orders._modal_new_costumer', ['customer' => new App\Models\Customer])
  @include('dashboard.orders._modal_product')
@endsection

@push('js')
  <script>
    const $customer = null;
    const is_creating_order = true;
    const $products = @json($products);
  </script>
  <script src="{{ asset('plugins/underscore/underscore.js') }}"></script>

  @include('plugins.google-maps')
  @include('plugins.select2')
  @include('plugins.sweetalert')
  @include('dashboard.customers-management.customers.js.customer-map')
  @include('dashboard.customers-management.customers.js.form')
  @include('dashboard.orders.js.form')
@endpush