@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 px-0">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify" aria-hidden="true"></i> {{ __('dashboard.orders.order') }} - #{{ $refund->id }} </div>
                        <div class="card-body">
                            <div class="container-fluid mb-3 px-0">
                                <!--  -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('dashboard.orders.customer') }}</label>
                                            <input class="form-control" type="text" value="{{ optional($refund->customer)->name }}" readOnly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('dashboard.orders.user') }}</label>
                                            <input class="form-control" type="text" value="{{ optional($refund->user)->name }}" readOnly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('dashboard.orders.box_id') }}</label>
                                            <input class="form-control" type="text" value="{{ optional($refund->box)->id }}" readOnly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('dashboard.orders.date') }}</label>
                                            <input class="form-control" type="text" value="{{ $refund->date }}" readOnly>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                                <br>
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="products-tab" data-toggle="tab" href="#products" role="tab" aria-controls="products" aria-selected="true">{{ __('dashboard.orders.products.index') }} devueltos</a>
                                    </li>
                                    @if ($refund->order)
                                    <li class="nav-item">
                                        <a class="nav-link" id="products-order-tab" data-toggle="tab" href="#products-order" role="tab" aria-controls="products-order" aria-selected="true">{{ __('dashboard.orders.products.index') }} cambiados (llevados)</a>
                                    </li>
                                    @endif
                                </ul>
                                <!--  -->
                                <div class="tab-content" id="myTabContent">
                                    <!--  -->
                                    <div class="tab-pane fade show active" id="products" role="tabpanel" aria-labelledby="products-tab">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table id="datatable_refund_products" class="table" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">ID venta</th>
                                                            <th scope="col">{{ __('dashboard.orders.products.name') }}</th>
                                                            <th>{{ __('dashboard.orders.products.color') }}</th>
                                                            <th>{{ __('dashboard.orders.products.size') }}</th>
                                                            <th>{{ __('dashboard.orders.products.qty') }}</th>
                                                            <th>{{ __('dashboard.orders.products.total') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($refund->products as $product)
                                                            <tr>
                                                                <td>{{ $product->order_product->order_id }}</td>
                                                                <td>{{ $product->product_name }}</td>
                                                                <td>{{ optional($product->color)->name }}</td>
                                                                <td>{{ optional($product->size)->name }}</td>
                                                                <td>{{ $product->qty }}</td>
                                                                <td>{{ $product->total }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--  -->
                                    @if ($refund->order)
                                    <div class="tab-pane fade" id="products-order" role="tabpanel" aria-labelledby="products-order-tab">
                                        @if (Auth::user()->can('viewany', App\Models\Order::class))
                                        <br>
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <a href="{{ route('ventas.show', [$refund->order->id]) }}"><i class="fa fa-eye" aria-hidden="true"></i> Ver detalles de venta realizado en esta devolución.</a>
                                            </div>
                                        </div>
                                        @endif
                                        <!--  -->
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
                                                        @foreach ($refund->order->products as $product)
                                                            <tr>
                                                                <td>{{ $product->product_name }}</td>
                                                                <td>{{ optional($product->color)->name }}</td>
                                                                <td>{{ optional($product->size)->name }}</td>
                                                                <td>{{ $product->qty }}</td>
                                                                <td>{{ $product->total }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            {{--  --}}
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="{{ route('devoluciones.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
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
    @include('dashboard.refunds.js.show')
@endpush