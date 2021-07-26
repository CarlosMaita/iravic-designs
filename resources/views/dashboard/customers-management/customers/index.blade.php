@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.customers-management.customers.index') }}</div>
                        <div class="card-body">
                            @can('create', App\Models\Customer::class)
                                <div class="row"> 
                                    <a href="{{ route('clientes.create') }}" class="btn btn-primary m-2 ml-auto">{{ __('dashboard.general.new_a') }}</a>
                                </div>
                                <br>
                            @endcan
                            {{-- Datatable --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="datatable_customers" class="table" width="100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{ __('dashboard.customers-management.customers.name') }}</th>
                                                <th scope="col">{{ __('dashboard.customers-management.customers.dni') }}</th>
                                                <th scope="col">{{ __('dashboard.customers-management.customers.telephone') }}</th>
                                                <th scope="col">{{ __('dashboard.customers-management.customers.qualification') }}</th>
                                                <th scope="col">{{ __('dashboard.customers-management.customers.zone') }}</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        </table>
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
    @include('dashboard.customers-management.customers.js.index')
@endpush