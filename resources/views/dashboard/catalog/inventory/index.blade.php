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
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.catalog.inventory.index') }}</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <a id="btn-download" href="{{route('catalog.inventory.download')}}" class="btn btn-primary mr-2" ><i class="fa fa-download"></i> Descargar Inventario</a>
                                    <a id="btn-upload" href="#" class="btn btn-primary mr-2"><i class="fa fa-upload"></i> Subir Inventario</a>
                                </div>
                            </div>
                            <br> 

                            {{-- @include('dashboard.catalog.products._filter') --}}
                            {{-- Datatable --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="datatable_inventory" class="table" width="100%">
                                            <thead>
                                                <tr>
                                                    <th scope="col">{{ __('dashboard.form.fields.general.name') }}</th>
                                                    <th scope="col">{{ __('dashboard.form.fields.products.code') }}</th>
                                                    <th scope="col">{{ __('dashboard.form.fields.inventories.is_regular') }}</th>
                                                    <th scope="col">{{ __('dashboard.form.fields.inventories.gender_size_color') }}</th>
                                                    <th scope="col">{{ __('dashboard.form.fields.inventories.stock_deposito') }}</th>
                                                    <th scope="col">{{ __('dashboard.form.fields.inventories.stock_local') }}</th>
                                                    <th scope="col">{{ __('dashboard.form.fields.inventories.stock_camioneta') }}</th>
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
    
    {{-- @include('dashboard.catalog.products._modal_stock_qty') --}}
@endsection

@push('js')
    @include('plugins.select2')
    @include('plugins.sweetalert')
    @include('dashboard.catalog.inventory.js.index')
@endpush