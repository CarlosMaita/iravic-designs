@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 px-0">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.catalog.stores.index') }}</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 d-flex  justify-content-end">
                                    @can('create', App\Models\Stores::class)
                                    <div>
                                        <a href="{{ route('depositos.create') }}" class="btn btn-primary ml-auto">{{ __('dashboard.general.new_o') }}</a>
                                    </div>
                                    @endcan
                                </div>
                            </div>
                            <br>
                            {{-- Datatable --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="datatable_stores" class="table" width="100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{ __('dashboard.form.fields.stores.name') }}</th>
                                                <th scope="col">{{ __('dashboard.form.fields.stores.type') }}</th>
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
@endsection

@push('js')
    @include('plugins.select2')
    @include('plugins.sweetalert')
    @include('dashboard.catalog.stores.js.index')
@endpush