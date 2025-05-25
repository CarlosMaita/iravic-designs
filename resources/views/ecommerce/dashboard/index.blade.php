@extends('ecommerce.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
        <div class="row">
          <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
              <div class="card">
                  <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.breadcrumb.dashboard') }}</div>
                  <div class="card-body">
                    Bienvenido - {{Auth::user()->name}}
                  </div>
              </div>
          </div>
        </div>
    </div>
  </div>
@endsection
