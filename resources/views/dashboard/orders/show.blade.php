@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            @if ($order->refund)
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-warning" role="alert">
                        Esta venta es un <b>cambio</b> originada de la devolucion <a href="{{ route('devoluciones.show', [$order->refund->id]) }}">#{{ $order->refund->id }}</a>
                    </div>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify" aria-hidden="true"></i> {{ __('dashboard.orders.order') }} - #{{ $order->id }} </div>
                        <div class="card-body">
                            <div class="container-fluid mb-3 px-0">
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
                                            <label>{{ __('dashboard.orders.subtotal') }}</label>
                                            <input class="form-control" type="text" value="{{ $order->subtotal }}" readOnly>
                                        </div>
                                    </div>
                                    {{--  --}}
                                    @if ($order->getRawOriginal('discount'))
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('dashboard.orders.discount') }}</label>
                                            <input class="form-control" type="text" value="{{ $order->discount }}" readOnly>
                                        </div>
                                    </div>
                                    @endif
                                    {{--  --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('dashboard.orders.total') }}</label>
                                            <input class="form-control" type="text" value="{{ $order->total }}" readOnly>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                                @if ($order->refund && Auth::user()->can('viewany', App\Models\Refund::class))
                                    <br>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <a href="{{ route('devoluciones.show', [$order->refund_id]) }}"><i class="fa fa-eye" aria-hidden="true"></i> Ver detalles de devolución (origen) del cuál se realizó este cambio.</a>
                                        </div>
                                    </div>
                                @endif
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
                                                            <th>{{ __('dashboard.orders.products.store_taken') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($order->products as $product)
                                                            <tr>
                                                                <td>{{ $product->product_name  }}</td>
                                                                <td>{{ optional($product->color)->name  }}</td>
                                                                <td>{{ optional($product->size)->name  }}</td>
                                                                <td>{{ $product->qty  }}</td>
                                                                <td>{{ $product->total  }}</td>
                                                                <td>{{ optional($product->store)->name  }}</td>
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
                                        <a href="{{ route('ventas.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
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
    @include('dashboard.orders.js.show')
@endpush