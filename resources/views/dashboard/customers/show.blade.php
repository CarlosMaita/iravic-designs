@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.customers-management.customers.details') }} - {{ $customer->name }}</div>
                        <div class="card-body">
                            <div class="container-fluid">
                                <!--  -->
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">Info</a>
                                    </li>
                                    <!--  -->
                                    <li class="nav-item">
                                        <a class="nav-link" id="account-status-tab" data-toggle="tab" href="#account-status" role="tab" aria-controls="account-status" aria-selected="true">Estado de Cuenta</a>
                                    </li>
                                    <!--  -->
                                    <li class="nav-item">
                                        <a class="nav-link" id="orders-tab" data-toggle="tab" href="#orders" role="tab" aria-controls="orders" aria-selected="true">Pedidos</a>
                                    </li>
                                </ul>
                                <!--  -->
                                <div class="tab-content" id="myTabContent">
                                    <!--  -->
                                    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                                        <div class="row mb-4 mt-3">
                                            <div class="col-12">
                                                <small class="form-text text-muted font-weight-bold text-success">{{ __('dashboard.form.labels.customer_personal_info') }}</small>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label>{{ __('dashboard.form.fields.customers.name') }}</label>
                                                    <input class="form-control" type="text" value="{{ $customer->name }}" readOnly>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label>{{ __('dashboard.form.fields.customers.telephone') }}</label>
                                                    <input class="form-control" type="text" value="{{ $customer->telephone }}" readOnly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label>{{ __('dashboard.form.fields.customers.dni') }}</label>
                                                    <input class="form-control" type="text" value="{{ $customer->dni }}" readOnly>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="dni_picture">{{ __('dashboard.form.fields.customers.dni_picture') }}</label>
                                                    {{-- @if($customer->dni_picture)  --}}
                                                    <div class="img-wrapper mt-3 mx-auto text-center" style="max-width: 320px;">
                                                        <img id="img-dni_picture" class="mt-3 img-fluid" src="{{ $customer->url_dni }}" alt="{{ __('dashboard.form.fields.customers.dni_picture') }}" />
                                                    </div>
                                                    {{-- @endif --}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label>{{ __('dashboard.form.fields.customers.zone') }}</label>
                                                    <input class="form-control" type="text" value="{{ optional($customer->zone)->name }}" readOnly>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label>{{ __('dashboard.form.fields.customers.qualification') }}</label>
                                                    <input class="form-control" type="text" value="{{ $customer->qualification }}" readOnly>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="container-fluid">
                                            <div class="row mb-4">
                                                <div class="col-12">
                                                    <small class="form-text text-muted font-weight-bold text-success">{{ __('dashboard.form.labels.customer_finance_info') }}</small>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.form.fields.customers.max_credit') }}</label>
                                                        <input class="form-control" type="text" value="{{ $customer->max_credit }}" readOnly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="receipt_picture">{{ __('dashboard.form.fields.customers.receipt_picture') }}</label>
                                                        
                                                        {{-- @if($customer->receipt_picture)  --}}
                                                        <div class="img-wrapper mt-3 mx-auto text-center position-relative" style="max-width: 320px;">
                                                            <img id="img-receipt_picture" class="mt-3 img-fluid" src="{{ $customer->url_receipt }}" alt="{{ __('dashboard.form.fields.customers.receipt_picture') }}" />
                                                        </div>
                                                        {{-- @endif --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div  class="container-fluid">
                                            <div class="row mb-4">
                                                <div class="col-12">
                                                    <small class="form-text text-muted font-weight-bold text-success">{{ __('dashboard.form.labels.customer_contact_info') }}</small>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.form.fields.customers.contact_name') }}</label>
                                                        <input class="form-control" type="text" value="{{ $customer->contact_name }}" readOnly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.form.fields.customers.contact_telephone') }}</label>
                                                        <input class="form-control" type="text" value="{{ $customer->contact_telephone }}" readOnly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.form.fields.customers.contact_dni') }}</label>
                                                        <input class="form-control" type="text" value="{{ $customer->contact_dni }}" readOnly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div  class="container-fluid">
                                            <div class="row mb-4">
                                                <div class="col-12">
                                                    <small class="form-text text-muted font-weight-bold text-success">{{ __('dashboard.form.labels.customer_address_info') }}</small>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.form.fields.customers.address') }}:</label>
                                                        <input class="form-control" type="text" value="{{ $customer->address }}" readOnly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <div class="col-md-12">
                                                    <div id="map-customer" style="height: 300px;"></div>
                                                </div>  
                                            </div>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="tab-pane fade" id="account-status" role="tabpanel" aria-labelledby="account-status-tab">
                                        <div class="container-fluid mb-4 mt-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.customers.total_buyed') }}</label>
                                                        <input class="form-control" type="text" value="{{ $customer->total_buyed }}" readOnly>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.customers.total_credit_give_for') }}</label>
                                                        <input class="form-control" type="text" value="{{ $customer->total_credit }}" readOnly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.customers.total_payments') }}</label>
                                                        <input class="form-control" type="text" value="{{ $customer->total_payments }}" readOnly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.customers.total_debt') }}</label>
                                                        <input class="form-control" type="text" value="{{ $customer->total_debt }}" readOnly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    @include('dashboard.orders._datatable', ['orders' => $orders])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--  --}}
                            <a href="{{ route('clientes.edit', [$customer->id]) }}" class="btn btn-success">{{ __('dashboard.form.edit') }}</a>
                            <a href="{{ route('clientes.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        const $customer = @json($customer);
    </script>
    @include('plugins.google-maps')
    @include('dashboard.customers.js.customer-map')
    @include('dashboard.customers.js.show')
@endpush