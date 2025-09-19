@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-archive"></i> Pagos Archivados
                            <div class="card-header-actions">
                                <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-arrow-left"></i> Volver a Pagos
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- Datatable --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="datatable_archived_payments" class="table" width="100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Cliente</th>
                                                <th scope="col">Orden</th>
                                                <th scope="col">Monto</th>
                                                <th scope="col">Estado</th>
                                                <th scope="col">Referencia</th>
                                                <th scope="col">Fecha</th>
                                                <th scope="col">Acciones</th>
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
    @include('plugins.sweetalert')
    @include('dashboard.payments.js.archived')
@endpush