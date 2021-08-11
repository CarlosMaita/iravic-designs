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
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table id="datatable_schedule_visits" class="table" width="100%">
                                            <thead>
                                                <tr>
                                                    <th scope="col">{{ __('dashboard.visits.customer') }}</th>
                                                    <th>{{ __('dashboard.visits.address') }}</th>
                                                    <th>{{ __('dashboard.visits.comment') }}</th>
                                                    <th>{{ __('dashboard.visits.responsable') }}</th>
                                                    <th>{{ __('dashboard.visits.completed') }}</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--  --}}
                            <a href="{{ route('agendas.edit', [$schedule->id]) }}" class="btn btn-success">{{ __('dashboard.form.edit') }}</a>
                            <a href="{{ route('agendas.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        const $schedule = @json($schedule);

        $(function() {
            // $('#datatable_visits').DataTable({
            //     pageLength: 25,
            // });
        });
    </script>
    
    @include('plugins.sweetalert')
    @include('dashboard.schedules.js.show')
@endpush