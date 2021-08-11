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
@endsection

@push('js')
    @include('plugins.sweetalert')
    @include('dashboard.schedules.js.show')
@endpush