@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      @if (Auth::user()->isAdmin())
        <div class="row">
          <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
              <div class="card">
                  <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.breadcrumb.dashboard') }}</div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-12">
                        <div class="alert alert-info">
                          <h4>Panel de Control</h4>
                          <p>Sales and refunds module has been removed. Use the navigation menu to access other system features like catalog management, customer management, and configuration.</p>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
          </div>
        </div>
      @endif
    </div>
  </div>
@endsection
