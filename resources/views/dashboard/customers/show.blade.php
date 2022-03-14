@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.customers.details') }} - {{ $customer->name }}</div>
                        <div class="card-body">
                            <div class="container-fluid px-0">
                                <!--  -->
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link @if(!$showOrdersTab) active @endif" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">Info</a>
                                    </li>
                                    <!--  -->
                                    <li class="nav-item">
                                        <a class="nav-link" id="account-status-tab" data-toggle="tab" href="#account-status" role="tab" aria-controls="account-status" aria-selected="true">Estado de Cuenta</a>
                                    </li>
                                    <!--  -->
                                    <li class="nav-item">
                                        <a class="nav-link @if($showOrdersTab) active @endif" id="orders-tab" data-toggle="tab" href="#orders" role="tab" aria-controls="orders" aria-selected="true">Ventas</a>
                                    </li>
                                    <!--  -->
                                    @if (count($customer->orders))
                                    <li class="nav-item">
                                        <a class="nav-link @if($showRefundsTab) active @endif" id="refunds-tab" data-toggle="tab" href="#refunds" role="tab" aria-controls="refunds" aria-selected="true">Devoluciones</a>
                                    </li>
                                    @endif
                                    <!--  -->
                                    <li class="nav-item">
                                        <a class="nav-link" id="payments-tab" data-toggle="tab" href="#payments" role="tab" aria-controls="payments" aria-selected="true">Pagos/Cobros</a>
                                    </li>
                                    <!--  -->
                                    <li class="nav-item">
                                        <a class="nav-link" id="debts-tab" data-toggle="tab" href="#debts" role="tab" aria-controls="debts" aria-selected="true">Deudas</a>
                                    </li>
                                    <!--  -->
                                    <li class="nav-item">
                                        <a class="nav-link" id="visits-tab" data-toggle="tab" href="#visits" role="tab" aria-controls="visits" aria-selected="true">Visitas</a>
                                    </li>
                                </ul>
                                <!--  -->
                                <div class="tab-content" id="myTabContent">
                                    <!--  -->
                                    @include('dashboard.customers._partials.personal_info')
                                    <!--  -->
                                    @include('dashboard.customers._partials.account_status')
                                    <!--  -->
                                    @include('dashboard.customers._partials.orders')
                                    <!--  -->
                                    @include('dashboard.customers._partials.refunds')
                                    <!--  -->
                                    @include('dashboard.customers._partials.payments')
                                    <!--  -->
                                    @include('dashboard.customers._partials.debts')
                                    <!--  -->
                                    @include('dashboard.customers._partials.visits')
                                </div>
                            </div>
                            {{--  --}}
                            <a href="{{ route('clientes.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
                            <a href="{{ route('clientes.edit', [$customer->id]) }}" class="btn btn-success">{{ __('dashboard.form.edit') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.debts.modal_form')
    @include('dashboard.payments.modal_form')
    @include('dashboard.visits.modal_form')
@endsection

@push('css')
    <style>
        .datepicker-dropdown {
            max-width: 300px;
        }
    </style>
@endpush

@push('js')
    <script>
        let $customer = @json($customer);
        let $datatable_operations = $("#datatable_operations");

        function updateDatatableOperations() {
            $datatable_operations.DataTable().ajax.reload();
        }

        function updateBalanceLabel(balance) {
            $('#balance-text').val(balance);
        }
    </script>

    @include('plugins.datepicker')
    @include('plugins.google-maps')
    @include('plugins.select2')
    @include('plugins.show_bind')
    @include('plugins.sweetalert')
    @include('dashboard.customers.js.customer-map')
    @include('dashboard.customers.js.show')
    @include('dashboard.debts.js.index')
    @include('dashboard.operations.js.index')
    @include('dashboard.payments.js.index')
    @include('dashboard.visits.js.index')
@endpush