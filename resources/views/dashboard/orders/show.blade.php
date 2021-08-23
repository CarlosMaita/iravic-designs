@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify" aria-hidden="true"></i> {{ __('dashboard.orders.order') }} - #{{ $order->id }} </div>
                        <div class="card-body">
                            <div class="container-fluid mb-3">
                                <!--  -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ __('dashboard.orders.customer') }}</label>
                                            <input class="form-control" type="text" value="{{ optional($order->customer)->name }}" readOnly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('dashboard.orders.user') }}</label>
                                            <input class="form-control" type="text" value="{{ optional($order->user)->name }}" readOnly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('dashboard.orders.box_id') }}</label>
                                            <input class="form-control" type="text" value="{{ optional($order->box)->id }}" readOnly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('dashboard.orders.date') }}</label>
                                            <input class="form-control" type="text" value="{{ $order->date }}" readOnly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('dashboard.orders.payment_method') }}</label>
                                            <input class="form-control" type="text" value="{{ $order->payment_method }}" readOnly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('dashboard.orders.total') }}</label>
                                            <input class="form-control" type="text" value="{{ $order->total }}" readOnly>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                                <br>
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="products-tab" data-toggle="tab" href="#products" role="tab" aria-controls="products" aria-selected="true">{{ __('dashboard.orders.products.index') }}</a>
                                    </li>
                                </ul>
                                <!--  -->
                                <div class="tab-content" id="myTabContent">
                                    <!--  -->
                                    <div class="tab-pane fade show active" id="products" role="tabpanel" aria-labelledby="products-tab">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table id="datatable_order_products" class="table" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">{{ __('dashboard.orders.products.name') }}</th>
                                                            <th>{{ __('dashboard.orders.products.color') }}</th>
                                                            <th>{{ __('dashboard.orders.products.size') }}</th>
                                                            <th>{{ __('dashboard.orders.products.qty') }}</th>
                                                            <th>{{ __('dashboard.orders.products.total') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($order->products as $product)
                                                            <tr>
                                                                <th>{{ $product->product_name }}</th>
                                                                <th>{{ optional($product->color)->name }}</th>
                                                                <th>{{ optional($product->size)->name }}</th>
                                                                <th>{{ $product->qty }}</th>
                                                                <th>{{ $product->total }}</th>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--  --}}
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="{{ route('pedidos.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
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
    @include('plugins.datatable')
    @include('dashboard.orders.js.show')
@endpush