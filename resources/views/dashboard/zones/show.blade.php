@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> Zona: {{ $zone->name }}</div>
                        <div class="card-body">
                            <div class="container-fluid px-0">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('dashboard.zones.position') }}</label>
                                            <input class="form-control" type="text" value="{{ $zone->position }}" readOnly>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>{{ __('dashboard.zones.address_destination') }}</label>
                                            <input class="form-control" type="text" value="{{ $zone->address_destination }}" readOnly>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 col-6 mt-2">
                                        <img src="http://maps.google.com/mapfiles/ms/icons/blue-dot.png" alt="Bueno">
                                        <span class="mr-4">Muy Bueno</span>
                                    </div>
                                    <div class="col-sm-3 col-6 mt-2">
                                        <img src="http://maps.google.com/mapfiles/ms/icons/green-dot.png" alt="Bueno">
                                        <span class="mr-4">Bueno</span>
                                    </div>
                                    <div class="col-sm-3 col-6 mt-2">
                                        <img src="http://maps.google.com/mapfiles/ms/icons/yellow-dot.png" alt="Malo">
                                        <span class="mr-4">Malo</span>
                                    </div>
                                    <div class="col-sm-3 col-6 mt-2">
                                        <img src="http://maps.google.com/mapfiles/ms/icons/red-dot.png" alt="Muy Malo">
                                        <span>Muy Malo</span>
                                    </div>
                                </div>
                                <br>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div id="map-zone" style="height: 400px;"></div>
                                    </div>  
                                </div>
                            </div>
                            {{--  --}}
                            <a href="{{ route('zonas.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
                            <a href="{{ route('zonas.edit', [$zone->id]) }}" class="btn btn-success">{{ __('dashboard.form.edit') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        let $zone = @json($zone);
    </script>

    @include('plugins.google-maps')
    @include('dashboard.zones.js.zone_map')
    @include('dashboard.zones.js.show')
@endpush