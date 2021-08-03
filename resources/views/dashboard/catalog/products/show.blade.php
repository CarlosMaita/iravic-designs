@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> Producto: {{ $product->name }} (Cod: {{$product->code}})</div>
                        <div class="card-body">
                            <!--  -->
                            <div class="container-fluid mb-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Tipo: <span class="text-danger"><b>{{ $product->is_regular ? 'Regular' : 'Con combinaciones' }}</b></span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Nombre</label>
                                            <input class="form-control" type="text" value="{{ $product->name }}" readOnly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Código</label>
                                            <input class="form-control" value="{{ $product->code }}" readOnly>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Género</label>
                                            <input class="form-control" value="{{ $product->gender }}" readOnly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Categoría</label>
                                            <input class="form-control" value="{{ optional($product->category)->name }}" readOnly>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Marca</label>
                                            <input class="form-control" value="{{ optional($product->brand)->name }}" readOnly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Precio</label>
                                            <input class="form-control" value="{{ $product->regular_price_str }}" readOnly>
                                        </div>
                                    </div>
                                    @if($product->product_combinations)
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Precio base activado para combinaciones</label>
                                            <input class="form-control" value="{{ $product->is_price_generic ? 'Si' : 'No' }}" readOnly>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <!--  -->
                                <br>
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    @if($product->is_regular) 
                                        <li class="nav-item">
                                            <a class="nav-link active" id="stocks-tab" data-toggle="tab" href="#stocks" role="tab" aria-controls="stocks" aria-selected="true">Atributos y Stocks</a>
                                        </li>
                                    @endif
                                    @if(!$product->is_regular) 
                                        <li class="nav-item">
                                            <a class="nav-link active" id="combinations-tab" data-toggle="tab" href="#combinations" role="tab" aria-controls="combinations" aria-selected="false">Combinaciones y Stocks</a>
                                        </li>
                                    @endif
                                </ul>
                                <!--  -->
                                <div class="tab-content" id="myTabContent">
                                    <!--  -->
                                    @if($product->is_regular)
                                        <div class="tab-pane fade show active" id="stocks" role="tabpanel" aria-labelledby="stocks-tab">
                                            <!--  -->
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Color</label>
                                                        <input class="form-control" value="{{ optional($product->color)->name }}" readOnly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Talla</label>
                                                        <input class="form-control" value="{{ optional($product->size)->name }}" readOnly>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--  -->
                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label>Stock Depósito</label>
                                                        <input class="form-control" value="{{ $product->stock_depot }}" readOnly>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label>Stock Local</label>
                                                        <input class="form-control" value="{{ $product->stock_local }}" readOnly>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label>Stock Camioneta</label>
                                                        <input class="form-control" value="{{ $product->stock_truck }}" readOnly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <!--  -->
                                    @if(!$product->is_regular)
                                        <div class="tab-pane fade show active" id="combinations" role="tabpanel" aria-labelledby="combinations-tab">
                                            <br>
                                            <!--  -->
                                            @foreach ($product->product_combinations as $key => $product_combination)
                                                <div class="container-fluid">
                                                    <!--  -->
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <p><b>Combinación #{{ ($key + 1) }}</b></p> 
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Color</label>
                                                                <input class="form-control" value="{{ optional($product_combination->color)->name }}" readOnly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Talla</label>
                                                                <input class="form-control" value="{{ optional($product_combination->size)->name }}" readOnly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--  -->
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label>Stock Depósito</label>
                                                                <input class="form-control" value="{{ $product_combination->stock_depot }}" readOnly>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label>Stock Local</label>
                                                                <input class="form-control" value="{{ $product_combination->stock_local }}" readOnly>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label>Stock Camioneta</label>
                                                                <input class="form-control" value="{{ $product_combination->stock_truck }}" readOnly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                            {{--  --}}
                            <a href="{{ route('productos.edit', [$product->id]) }}" class="btn btn-success">{{ __('dashboard.form.edit') }}</a>
                            <a href="{{ route('productos.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection