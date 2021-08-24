@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.schedules.details') }} (<b>{{ $schedule->date }}</b>)</div>
                        <div class="card-body">
                            <div class="container-fluid px-0">
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-between">
                                        <a id="btn-open-map" href="#" class="link"><i class="fas fa-globe-americas"></i> {{ __('dashboard.schedules.view-map') }}</a>
                                    </div>
                                </div>
                                <br>
                                <!--  -->
                                {{-- Datatable --}}
                                @include('dashboard.schedules._visits_datatable')
                            </div>
                            {{--  --}}
                            <a href="{{ route('agendas.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.schedules._modal_map')
    @include('dashboard.schedules._modal_visits')
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        let $schedule = @json($schedule);
        let $visits = @json($visits);
    </script>

    @include('plugins.google-maps')
    @include('plugins.select2')
    @include('plugins.sweetalert')
    @include('dashboard.schedules.js.schedule_map')
    @include('dashboard.schedules.js.show')
@endpush