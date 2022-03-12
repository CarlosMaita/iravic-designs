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

  <style>
    .datepicker-dropdown {
        max-width: 300px;
    }
  </style>
@endpush

@section('content')
  <div class="container-fluid">
      <div class="animated fadeIn">
          <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 px-0">
                  <div class="card">
                      <div class="card-header"><i class="fa fa-align-justify"></i> Crear nueva devolución</div>
                      <div class="card-body px-2">
                          @include('dashboard.refunds._form')
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
@endsection

@push('js')
  @include('plugins.datepicker')
  @include('plugins.select2')
  @include('plugins.sweetalert')
@endpush