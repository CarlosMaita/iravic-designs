@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header">@if($box->closed) <i class="fa fa-lock" aria-hidden="true"></i> @else <i class="fa fa-unlock" aria-hidden="true"></i> @endif {{ __('dashboard.boxes.box') }} - #{{ $box->id }} </div>
                        <div class="card-body">
                            <div class="container-fluid px-0">
                                <!--  -->
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <!--  -->
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
                                    <!--  -->
                                    <li class="nav-item">
                                        <a class="nav-link" id="payments-tab" data-toggle="tab" href="#payments" role="tab" aria-controls="orders" aria-selected="true">Pagos/Cobros</a>
                                    </li>
                                </ul>
                                <!--  -->
                                <div class="tab-content" id="myTabContent">
                                    <!--  -->
                                    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                                        <div class="container-fluid mt-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>ID</label>
                                                        <input class="form-control" type="text" value="{{ $box->id }}" readOnly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.boxes.user') }}</label>
                                                        <input class="form-control" type="text" value="{{ optional($box->user)->name }}" readOnly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.boxes.closed') }}</label>
                                                        <input class="form-control" type="text" value="{{ $box->closed ? __('dashboard.general.yes') : __('dashboard.general.no') }}" readOnly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.boxes.date') }}</label>
                                                        <input class="form-control" type="text" value="{{ $box->date }}" readOnly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.boxes.date_start') }}</label>
                                                        <input class="form-control" type="text" value="{{ $box->date_start }}" readOnly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.boxes.date_end') }}</label>
                                                        <input class="form-control" type="text" value="{{ $box->date_end }}" readOnly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.boxes.cash_initial') }}</label>
                                                        <input class="form-control" type="text" value="{{ number_format($box->cash_initial, 2, ',', '.') }}" readOnly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.boxes.total_payed') }}</label>
                                                        <input class="form-control" type="text" value="{{ $box->total_payed}}" readOnly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="tab-pane fade" id="account-status" role="tabpanel" aria-labelledby="account-status-tab">
                                        <div class="container-fluid mt-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.boxes.cash_initial') }}</label>
                                                        <input class="form-control" type="text" value="$ {{ number_format($box->cash_initial, 2, ',', '.') }}" readOnly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.boxes.cash_in_box') }}</label>
                                                        <input class="form-control" type="text" value="{{ $box->total_cash_in_box}}" readOnly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.boxes.total_cash') }}</label>
                                                        <input class="form-control" type="text" value="{{ $box->total_cash}}" readOnly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.boxes.total_card') }}</label>
                                                        <input class="form-control" type="text" value="{{ $box->total_card}}" readOnly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.boxes.total_bankwire') }}</label>
                                                        <input class="form-control" type="text" value="{{ $box->total_bankwire}}" readOnly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.boxes.total_credit') }}</label>
                                                        <input class="form-control" type="text" value="{{ $box->total_credit}}" readOnly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.boxes.total_payed') }}</label>
                                                        <input class="form-control" type="text" value="{{ $box->total_payed}}" readOnly>
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
                                    <!--  -->
                                    <div class="tab-pane fade" id="payments" role="tabpanel" aria-labelledby="payments-tab">
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    @include('dashboard.payments._datatable')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('cajas.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
                            <a href="{{ route('cajas.edit', [$box->id]) }}" class="btn btn-success">{{ __('dashboard.form.edit') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        const $box = @json($box);
        
        $(function() {
            $('#datatable_orders').DataTable({
                pageLength: 25,
            });

            // $('#orders')
            // // .bind('beforeShow', function() {
            // // }) 
            // .bind('afterShow', function() {
            //     // $('#orders').removeAttr('style');
            //     $('#payments').removeAttr('style');
            // })
            // .show(1000, function() {
            //     $('#datatable_orders').DataTable()
            //         .columns.adjust()
            //         .responsive.recalc();
            // })
            // .show();

            $('#orders-tab').on('click', function(e) {
                setTimeout(function(e) {
                    DATATABLE_RESOURCE.DataTable()
                    .columns.adjust()
                    .responsive.recalc();
                }, 1000);
            });
        });
    </script>

    @include('plugins.show_bind')
    @include('plugins.sweetalert')
    @include('dashboard.payments.js.index')
@endpush