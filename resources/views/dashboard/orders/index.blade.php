@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.orders.index') }}</div>
                        <div class="card-body">
                            @can('create', App\Models\Order::class)
                                <div class="row"> 
                                    <div class="col-12 text-right">
                                        <a href="{{ route('ventas.create') }}" class="btn btn-primary ml-auto">{{ __('dashboard.general.new_o') }}</a>
                                    </div>
                                </div>
                                <br>
                            @endcan
                            {{-- Datatable --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        @include('dashboard.orders._datatable')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    @include('plugins.sweetalert')
    @include('dashboard.orders.js.index')
@endpush