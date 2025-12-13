@extends('dashboard.base')

@push('css')
    <style>
        .select2-container {
            width: 100%!important;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 px-0">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.catalog.products.index') }}</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 d-flex  justify-content-between">
                                    <div>
                                        <a id="btn-download" href="#"><i class="fa fa-download"></i> Descargar Catálogo</a>
                                    </div>
                                    <div class="d-flex gap-2 justify-content-end">
                                        <div>
                                            <a id="btn-download-inventory" href="{{route('catalog.inventory.download')}}" class="btn btn-primary mr-2" ><i class="fa fa-download"></i> Descargar Inventario</a>
                                        </div>
                                        <div>
                                            <button id="btn-upload" class="btn btn-primary mr-2" data-toggle="modal" data-target="#view_import_inventory">
                                                <i class="fa fa-upload"></i>
                                                Subir Inventario
                                            </button>
                                        </div>
                                                                                <div>
                                            <a href="{{ route('productos.create') }}" class="btn btn-primary ml-auto">{{ __('dashboard.general.new_o') }}</a>
                                        </div>
                                                                            </div>
                                </div>
                            </div>
                            <br>
                            {{--  --}}
                            @include('dashboard.catalog.products._filter')
                            {{-- Datatable --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="datatable_products" class="table" width="100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">Imagen</th>
                                                <th scope="col">{{ __('dashboard.form.fields.general.name') }}</th>
                                                <th scope="col">{{ __('dashboard.form.fields.products.code') }}</th>
                                                <th scope="col">{{ __('dashboard.form.fields.products.gender') }}</th>
                                                <th scope="col">{{ __('dashboard.form.fields.products.brand') }}</th>
                                                <th scope="col">{{ __('dashboard.form.fields.products.category') }}</th>
                                                {{-- <th scope="col">{{ __('dashboard.form.fields.products.combinations') }}</th> --}}
                                                <th scope="col">{{ __('dashboard.form.fields.products.price') }}</th>
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
    {{--  --}}
    @include('dashboard.catalog.products._modal_stock_qty')
    @include('dashboard.catalog.products._modal_import_inventory')
@endsection

@push('js')
    @include('plugins.select2')
    @include('plugins.sweetalert')
    @include('dashboard.catalog.products.js.index')
@endpush