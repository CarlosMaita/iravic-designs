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
                    {{--  filters --}}
                    @include('dashboard.homepage._filters')  
                    <br>
                    {{--  content --}}
                    @include('dashboard.homepage._cards')
                  </div>
              </div>
          </div>
        </div>
      @endif
    </div>
  </div>
@endsection

@push('js')
  @include('plugins.datepicker')
  @include('dashboard.homepage.js.homepage')
@endpush

@push("css")
  @include('dashboard.homepage.css.homepage')
@endpush
