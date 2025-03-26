@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.zones.edit') }} - {{ $zone->name }}</div>
                        <div class="card-body">
                          <form id="form-zones" method="POST" action="{{ route('zonas.update', [$zone->id]) }}">
                            @csrf
                            @method('PUT')
                            @include('dashboard.zones._form')
                            <a href="{{ route('zonas.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
                            <button class="btn btn-success" type="submit">{{ __('dashboard.form.update') }}</button>
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
    const $zone = @json($zone);
  </script>
  <script src="{{ asset('plugins/underscore/underscore.js') }}"></script>

  @include('plugins.google-maps')
  @include('dashboard.zones.js.zone_form_map')
  @include('dashboard.zones.js.form')
@endpush