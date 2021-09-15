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
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 px-0">
                  <div class="card">
                      <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.orders.create') }}</div>
                      <div class="card-body px-2">
                        <form id="form-refunds" method="POST" action="{{ route('devoluciones.store') }}">
                          @csrf
                          @if (!empty($customerParam))
                            <input type="hidden" name="customer_param" value="{{ $customerParam->id }}">
                          @endif
                          @include('dashboard.refunds._form')
                          @include('dashboard.refunds._modal_discount')
                          <hr>
                          <div class="container-fluid">
                            <div class="row">
                              <div class="col-md-12 justify-content-end">
                                {{-- <button class="btn btn-success" type="submit">{{ __('dashboard.form.create') }}</button> --}}
                                <a href="{{ route('devoluciones.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
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
  
  @include('dashboard.refunds._modal_product')
  @include('dashboard.refunds._modal_product_refund')
@endsection

@push('js')
  <script>
    const $customer = null;
    const is_creating_refund = true;
    const $products = @json($products);
    
    var $productsForRefund = @json($productsForRefund);
  </script>

  @include('plugins.select2')
  @include('plugins.sweetalert')
  @include('dashboard.refunds.js.form')
@endpush