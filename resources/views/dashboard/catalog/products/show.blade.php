@extends('dashboard.base')

@push('css')
    <style>
        @media screen and (min-width: 767px){
            .css-g4fmj2 {
                padding-bottom: 24px;
                overflow-y: visible;
            }
        }
        .css-g4fmj2 {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            font-size: 14px;
            line-height: 20px;
            position: relative;
            height: 100%;
            /* padding: 0px 0px 176px; */
            scroll-behavior: smooth;
            overflow-y: auto;
        }
        .css-pmqufv {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            padding-top: 24px;
        }
        .css-oxzfpw {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            padding-left: 24px;
            padding-right: 24px;
        }
        .css-7tkdw6 {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            display: flex;
            padding: 24px;
            border-style: solid;
            border-width: 0px 0px 1px;
            border-color: rgb(234, 236, 239);
        }
        .css-1kzpntp {
            box-sizing: border-box;
            margin: 20px 8px 0px 0px;
            min-width: 0px;
            display: flex;
        }
        .css-vwdmr0 {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            display: flex;
            width: 32px;
            position: relative;
            padding-top: 24px;
            padding-bottom: 24px;
            flex-direction: column;
            -webkit-box-pack: justify;
            justify-content: space-between;
            -webkit-box-align: center;
            align-items: center;
        }
        .css-13zymhf {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            width: 5px;
            height: 5px;
            border-radius: 100%;
            border-width: 1px;
            border-style: solid;
            border-color: rgb(14, 203, 129);
        }
        .css-11mpmlu {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            flex: 1 1 0%;
            width: 0px;
            border-width: 0px 1px 0px 0px;
            border-style: dashed;
            border-color: rgb(234, 236, 239);
        }
        .css-1a1w98z {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            width: 5px;
            height: 5px;
            border-radius: 100%;
            border-width: 1px;
            border-style: solid;
            border-color: rgb(246, 70, 93);
        }
        .css-38fup1 {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            display: flex;
            cursor: pointer;
            -webkit-box-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            align-items: center;
            width: 32px;
            height: 32px;
            border-radius: 32px;
            background-color: rgb(245, 245, 245);
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%) rotate(90deg);
        }
        .css-124czaz {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            color: rgb(112, 122, 138);
            font-size: 16px;
            fill: rgb(112, 122, 138);
            width: 1em;
            height: 1em;
        }
        .css-1pysja1 {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            flex: 1 1 0%;
        }
        .css-bz1qgm {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            display: flex;
            width: 100%;
            flex-wrap: wrap;
        }
        @media screen and (min-width: 767px) {
            .css-1efkrz1 {
                flex: 1 1 0%;
                width: auto;
            }
        }
        .css-1efkrz1 {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            flex: 0 0 auto;
            width: 100%;
        }
        .css-yc6oq3 {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
        }
        .css-51ezhr {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            display: flex;
            width: 100%;
            -webkit-box-align: center;
            align-items: center;
            color: rgb(71, 77, 87);
            font-size: 14px;
        }
        .css-1xcjeua {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            display: flex;
            width: 100%;
            height: 48px;
            background-color: rgb(245, 245, 245);
            padding-left: 12px;
            padding-right: 12px;
            border-radius: 4px;
            -webkit-box-pack: justify;
            justify-content: space-between;
            -webkit-box-align: center;
            align-items: center;
        }
        .css-1pysja1 {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            flex: 1 1 0%;
            
        }
        select.css-1pysja1{
            color: inherit;
            background: transparent;
            border: none;
        }
}
        .css-oxzfpw {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            padding-left: 24px;
            padding-right: 24px;
        }
        .css-1pxm4lx {
            box-sizing: border-box;
            margin: 24px 0px 0px;
            min-width: 0px;
        }
        .css-51ezhr {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            display: flex;
            width: 100%;
            -webkit-box-align: center;
            align-items: center;
            color: rgb(71, 77, 87);
            font-size: 14px;
        }
        .css-13mjbma {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            position: relative;
            outline: none;
        }
        .css-1mxpxp {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            display: inline-flex;
            position: relative;
            -webkit-box-align: center;
            align-items: center;
            line-height: 1.6;
            background-color: rgb(245, 245, 245);
            border-radius: 4px;
            border-width: 1px;
            border-style: solid;
            border-color: transparent;
            height: 48px;
            width: 100%;
            cursor: pointer;
        }
        .css-1mxpxp input {
            color: rgb(30, 35, 41);
            font-size: 14px;
            border-radius: 4px;
            padding-left: 12px;
            padding-right: 12px;
        }
        .css-1jpgac0 {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            width: 100%;
            height: 100%;
            padding: 0px;
            outline: none;
            border: none;
            opacity: 1;
            visibility: hidden;
            cursor: pointer;
            background-color: transparent;
        }
        .css-cr60ng {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            display: flex;
            -webkit-box-pack: justify;
            justify-content: space-between;
            width: 100%;
        }
        .css-vurnku {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
        }
        .css-19gx5t6 {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            font-size: 14px;
            color: rgb(112, 122, 138);
            line-height: 20px;
        }
        .css-6hm6tl {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            color: rgb(30, 35, 41);
        }
        .css-p1qrqm {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            padding-left: 4px;
            padding-right: 4px;
        }
        .css-17mzxiv {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            display: inline-flex;
            position: relative;
            -webkit-box-align: center;
            align-items: center;
            line-height: 1.6;
            background-color: rgb(245, 245, 245);
            border-radius: 4px;
            border-width: 1px;
            border-style: solid;
            border-color: transparent;
            font-size: 14px;
            width: 100%;
            height: 48px;
        }

        .css-17mzxiv input {
            color: rgb(30, 35, 41);
            font-size: 14px;
            border-radius: 4px;
            padding-left: 12px;
            padding-right: 12px;
        }
        .css-16fg16t {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            width: 100%;
            height: 100%;
            padding: 0px;
            outline: none;
            border: none;
            background-color: inherit;
            opacity: 1;
        }
        .css-17mzxiv .bn-input-suffix {
            flex-shrink: 0;
            margin-left: 4px;
            margin-right: 4px;
            font-size: 14px;
        }
        .css-vurnku {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
        }
        .css-1ii0qmr {
            box-sizing: border-box;
            margin: 0px 8px 0px 0px;
            min-width: 0px;
            display: flex;
            -webkit-box-align: center;
            align-items: center;
        }
        .css-3bhv7e {
            box-sizing: border-box;
            margin: 0px 0px 0px 8px;
            min-width: 0px;
            font-size: 16px;
            color: rgb(201, 148, 0);
            line-height: 24px;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 px-0">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> Producto: {{ $product->name }} (Cod: {{$product->code}})</div>
                        <div class="card-body px-0">
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
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Descripción</label>
                                            <textarea class="form-control" id="description" rows="2" readOnly>{{ $product->description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Código</label>
                                            <input class="form-control" value="{{ $product->code }}" readOnly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Categoría</label>
                                            <input class="form-control" value="{{ optional($product->category)->name }}" readOnly>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="row">
                                    @if($product->category->baseCategory->has_gender)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Género</label>
                                            <input class="form-control" value="{{ $product->gender }}" readOnly>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Marca</label>
                                            <input class="form-control" value="{{ optional($product->brand)->name }}" readOnly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Precio</label>
                                            <input class="form-control" value="{{ $product->regular_price_str }}" readOnly>
                                        </div>
                                    </div>
                                    @can('prices-per-method-payment')
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Precio con Tarjeta de Credito</label>
                                            <input class="form-control" value="{{ $product->regular_price_card_credit_str }}" readOnly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Precio con Credito</label>
                                            <input class="form-control" value="{{ $product->regular_price_credit_str }}" readOnly>
                                        </div>
                                    </div>
                                    @endcan
                                </div>
                                <!--  -->
                                <br>
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    @if($product->is_regular) 
                                        <li class="nav-item">
                                            <a class="nav-link active" id="stocks-tab" data-toggle="tab" href="#stocks" role="tab" aria-controls="stocks" aria-selected="true">Stocks</a>
                                        </li>
                                    @endif
                                    @if(!$product->is_regular) 
                                        <li class="nav-item">
                                            <a class="nav-link active" id="combinations-tab" data-toggle="tab" href="#combinations" role="tab" aria-controls="combinations" aria-selected="false">Combinaciones y Stocks</a>
                                        </li>
                                    @endif
                                    <li class="nav-item">
                                        <a class="nav-link" id="multimedia-tab" data-toggle="tab" href="#multimedia" role="tab" aria-controls="multimedia" aria-selected="false">Multimedia</a>
                                    </li>
                                </ul>
                                <!--  -->
                                <div class="tab-content" id="myTabContent">
                                    <!--  -->
                                    @if($product->is_regular)
                                        <div class="tab-pane fade show active" id="stocks" role="tabpanel" aria-labelledby="stocks-tab">
                                            <!--  Stores for product regular  -->
                                            <div class="row mt-3">
                                                @foreach ($product->stores as $key => $store)
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="d-flex justify-content-between">
                                                            <span>Stock <i class="text-muted">({{ $store->name }})</i></span>
                                                            {{--  --}}
                                                            <div class="btn-group dropleft">
                                                                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    Opciones
                                                                </button>
                                                                {{-- Dropdown --}}
                                                                <div class="dropdown-menu">
                                                                    {{-- Modificar  --}}
                                                                    @can('update', $product)
                                                                        <span class="dropdown-item border-bottom modify-stock" 
                                                                        type="button" 
                                                                        data-id="{{ $product->id }}" 
                                                                        data-stock-id = "{{ $store->id }}"
                                                                        data-stock-name = "{{ $store->name }}"
                                                                        data-qty="{{ $store->pivot->stock }}">Modificar</span>
                                                                    @endcan
                                                                    {{-- Transferir --}}
                                                                    @can('create', App\Models\ProductStockTransfer::class)
                                                                        @if (Auth::user()->isAdmin() || Auth::user()->getColumnStock() == 'stock_truck')
                                                                            <span class="dropdown-item border-bottom view-transfer-stock"
                                                                                type="button" 
                                                                                id="btn_{{ $product->id }}_stock_{{ $store->id }}"
                                                                                data-id="{{ $product->id }}"
                                                                                data-stock-origin="stock_truck"
                                                                                data-stock-destination="stock_local"
                                                                                data-stock-origin-id="{{ $store->id }}"
                                                                                data-stock-origin-name="{{ $store->name }}"
                                                                                data-stock="{{ $store->pivot->stock }}">
                                                                            Transferir</span>
                                                                        @endif
                                                                    @endcan
                                                                    {{-- Historial --}}
                                                                    <span class="dropdown-item  view-stock-history"
                                                                        type="button"  
                                                                        data-id="{{ $product->id }}"
                                                                        data-stock-name="{{ $store->name }}"
                                                                        data-stock="stock_truck">Historial</span>
                                                                </div>
                                                                {{-- end dropdown --}}
                                                            </div>
                                                        </label>
                                                        <input class="form-control" value="{{ $store->pivot->stock }}" readOnly>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            <!--  End stores for product regular  -->

                                            <div class="row">
                                                <div class="col-12">
                                                    <p><b>Total stock:</b> {{ $product->stock_total }}</p>
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
                                                <div class="container-fluid px-0">
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
                                                                <label>Color en texto</label>
                                                                <input class="form-control" value="{{ $product_combination->text_color }}" readOnly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        @if($product->category->baseCategory->has_size)
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Talla</label>
                                                                <input class="form-control" value="{{ optional($product_combination->size)->name }}" readOnly>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Código</label>
                                                                <input class="form-control" value="{{ $product_combination->real_code }}" readOnly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Precio</label>
                                                                <input class="form-control" value="{{ $product_combination->regular_price_str }}" readOnly>
                                                            </div>
                                                        </div>
                                                        @can('prices-per-method-payment')
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Precio con Tarjeta de Credito</label>
                                                                <input class="form-control" value="{{ $product_combination->regular_price_card_credit_str }}" readOnly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Precio con Credito</label>
                                                                <input class="form-control" value="{{ $product_combination->regular_price_credit_str }}" readOnly>
                                                            </div>
                                                        </div>
                                                        @endcan
                                                    </div>

                                                    {{-- stores for product NO Regular --}}
                                                    <div class="row mt-3">
                                                        @foreach ($product_combination->stores as $key => $store)
                                                        <div class="col-lg-4">
                                                            <div class="form-group">
                                                                <label class="d-flex justify-content-between">
                                                                    <span>Stock <i class="text-muted">({{ $store->name }})</i></span>
                                                                    <div class="btn-group dropleft">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Opciones
                                                                        </button>
                                                                        {{-- Dropdown --}}
                                                                        <div class="dropdown-menu">
                                                                            {{-- Modificar  --}}
                                                                            @can('update', $product_combination)
                                                                                <span class="dropdown-item border-bottom modify-stock" 
                                                                                type="button" 
                                                                                data-id="{{ $product_combination->id }}" 
                                                                                data-stock-id = "{{ $store->id }}"
                                                                                data-stock-name = "{{ $store->name }}"
                                                                                data-qty="{{ $store->pivot->stock }}">Modificar</span>
                                                                            @endcan
                                                                            {{-- Transferir --}}
                                                                            @can('create', App\Models\ProductStockTransfer::class)
                                                                                @if (Auth::user()->isAdmin() || Auth::user()->getColumnStock() == 'stock_truck')
                                                                                    <span class="dropdown-item border-bottom view-transfer-stock"
                                                                                        type="button" 
                                                                                        id="btn_{{ $product_combination->id }}_stock_{{ $store->id }}"
                                                                                        data-id="{{ $product_combination->id }}"
                                                                                        data-stock-origin="stock_truck"
                                                                                        data-stock-destination="stock_local"
                                                                                        data-stock-origin-id="{{ $store->id }}"
                                                                                        data-stock-origin-name="{{ $store->name }}"
                                                                                        data-stock="{{ $store->pivot->stock }}">
                                                                                    Transferir</span>
                                                                                @endif
                                                                            @endcan
                                                                            {{-- Historial --}}
                                                                            <span class="dropdown-item  view-stock-history"
                                                                            type="button"  
                                                                            data-id="{{ $product_combination->id }}" 
                                                                            data-stock-name="{{ $store->name }}"   
                                                                            data-stock="stock_truck">Historial</span>
                                                                        </div>
                                                                        {{-- end dropdown --}}
                                                                    </div>
                                                                </label>
                                                                <input class="form-control" value="{{ $store->pivot->stock }}" readOnly>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    <!--  End stores for product NO regular  -->

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <p><b>Total stock:</b> {{ $product_combination->stock_total }}</p>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <!--  -->
                                    <div class="tab-pane fade" id="multimedia" role="tabpanel" aria-labelledby="multimedia-tab">
                                        <br>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table id="datatable_images" class="table" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Foto</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--  --}}
                            <a href="{{ route('productos.index') }}" class="btn btn-primary ml-3">{{ __('dashboard.form.back to list') }}</a>
                            
                            @if (Auth::user()->can('update', $product)) 
                                <a
                                href="{{ route('productos.edit', [$product->id]) }}"
                                class="btn btn-success">{{
                                __('dashboard.form.edit')
                                }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--  --}}
    @include('dashboard.catalog.products._modal_stock_history')
    @include('dashboard.catalog.products._modal_stock_modify')
    @include('dashboard.catalog.products._modal_stock_transfer')
@endsection

@push('js')
    @include('dashboard.catalog.products.js.show')
@endpush