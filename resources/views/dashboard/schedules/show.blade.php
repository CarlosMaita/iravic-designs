@extends('dashboard.base')

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
                                <!--  -->
                                @foreach ($zones as $zone)
                                    @include('dashboard.schedules._partials.zone', ['zone' => $zone])
                                @endforeach
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

        /*
        var collapse_element = $('#collapseExample');

        $('.zone-item').on('click', function(e) {
            var expanded = this.getAttribute('aria-expanded');
            var target_id = this.dataset.target;
            var target = $(target_id);
            
            if (expanded == 'false') {
                target.collapse('hide');
                collapse_element.collapse('hide');

                setTimeout(function() { 
                    collapse_element.collapse('hide');
                }, 500);
            } 
        });

        $('#test').on('click', function(e) {
            collapse_element.collapse('hide');
        });
        */
    </script>

    @include('plugins.google-maps')
    @include('plugins.select2')
    @include('plugins.sweetalert')
    @include('dashboard.schedules.js.schedule_map')
    @include('dashboard.schedules.js.show')
@endpush