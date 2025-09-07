@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-credit-card"></i> Pagos</div>
                        <div class="card-body">
                            {{-- Filters --}}
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <select id="status_filter" class="form-control">
                                        <option value="">Todos los estados</option>
                                        @foreach($statuses as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            {{-- Datatable --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="datatable_payments" class="table" width="100%">
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
    @include('dashboard.payments.js.index')
@endpush