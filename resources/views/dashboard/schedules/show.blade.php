@extends('dashboard.base')

@push('css')
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
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.schedules.details') }} (<b>{{ $schedule->date }}</b>)</div>
                        <div class="card-body px-2">
                            <div class="container-fluid px-0">
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-between">
                                        <a id="btn-open-map" href="#" class="link"><i class="fas fa-globe-americas"></i> {{ __('dashboard.schedules.view-map') }}</a>
                                    </div>
                                </div>
                                <br>
                                @if ($schedule->hasVisitsWithoutPosition())
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="alert alert-warning" role="alert">
                                                Hay clientes que no tienen su posicion ordenada. Haz click <button class="btn-sort-schedule btn btn-link p-0" type="button">aqui <i class="fas fa-sync"></i></button> para ordenar los clientes de la agenda por zonas.
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <br>
                                <!--  -->
                                @foreach ($zones as $zone)
                                    @include('dashboard.schedules._partials.zone', ['zone' => $zone])
                                @endforeach
                            </div>
                            {{--  --}}
                            <a href="{{ route('agendas.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
                            <button class="btn-sort-schedule btn btn-warning text-white">Ordenar</button>
                            @if ($sortBy == 'asc')
                            <a href="{{ route('agendas.show', [$schedule->id]) }}?sort=desc" class="btn btn-dark text-white">Recorrido de Vuelta</a>
                            @else
                            <a href="{{ route('agendas.show', [$schedule->id]) }}?sort=asc" class="btn btn-dark text-white">Recorrido de Ida</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.schedules._modal_map')
    @include('dashboard.schedules._modal_responsable')
    @include('dashboard.payments.modal_installment_form')
    @include('dashboard.visits.modal_form')
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        let $schedule = @json($schedule),
            $visits = @json($visits),
            $zones = @json($zones);
    </script>

    @include('plugins.datepicker')
    @include('plugins.google-maps')
    @include('plugins.select2')
    @include('plugins.sweetalert')
    @include('dashboard.schedules.js.routes')
    @include('dashboard.schedules.js.schedule_map')
    @include('dashboard.schedules.js.show')
    @include('dashboard.payments.js.index')

@endpush