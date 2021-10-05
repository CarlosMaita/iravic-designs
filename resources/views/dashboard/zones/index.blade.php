@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.zones.index') }}</div>
                        <div class="card-body">
                            @can('create', App\Models\Zone::class)
                                <div class="row"> 
                                    <a href="{{ route('zonas.create') }}" class="btn btn-primary m-2 ml-auto">{{ __('dashboard.general.new_a') }}</a>
                                </div>
                                <br>
                            @endcan
                            <div id="zonas-container" class="list-group">
                                @foreach ($zones as $zone)
                                    @include('dashboard.zones._partials.sortable_item', [
                                        'customers_qty' => count($zone->customers),
                                        'zone' => $zone
                                    ])
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .dataTables_length {
            display: none;
        }
    </style>
@endpush

@push('js')
    @include('plugins.sweetalert')
    @include('plugins.sortablejs')
    @include('dashboard.zones.js.sortablejs')
    @include('dashboard.zones.js.index')
@endpush