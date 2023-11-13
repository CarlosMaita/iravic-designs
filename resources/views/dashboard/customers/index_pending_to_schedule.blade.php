@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> Clientes pendiente por agendar</div>
                        <div class="card-body">
                            {{-- Datatable --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="datatable_customers_pending_to_schedule" class="table" width="100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{ __('dashboard.customers.name') }}</th>
                                                <th scope="col">{{ __('dashboard.customers.dni') }}</th>
                                                <th scope="col">{{ __('dashboard.customers.telephone') }}</th>
                                                <th scope="col">{{ __('dashboard.customers.qualification') }}</th>
                                                <th scope="col">{{ __('dashboard.customers.zone') }}</th>
                                                <th scope="col">{{ __('dashboard.customers.balance') }}</th>
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
    @include('dashboard.customers.js.index_pending_to_schedule')
@endpush