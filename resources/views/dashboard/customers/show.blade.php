@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white d-flex align-items-center">
                            <i class="fa fa-user-circle mr-2"></i>
                            <span>
                                {{ __('dashboard.customers.details') }} - {{ $customer->name ?? '' }}
                            </span>
                        </div>
                        <div class="card-body">
                            @if(isset($customer))
                            <form>
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label font-weight-bold">Nombre y DNI</label>
                                        <div class="form-control-plaintext border rounded bg-light px-3 py-2">
                                            {{ $customer->name ?? '' }}
                                            @if(!empty($customer->dni))
                                                <span class="text-muted ml-2">({{ $customer->dni }})</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label font-weight-bold">Correo electrónico</label>
                                        <div class="form-control-plaintext border rounded bg-light px-3 py-2">
                                            {{ $customer->email ?? '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label font-weight-bold">Teléfono</label>
                                        <div class="form-control-plaintext border rounded bg-light px-3 py-2">
                                            {{ $customer->cellphone ?? '' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label font-weight-bold">Calificación</label>
                                        <div class="form-control-plaintext border rounded bg-light px-3 py-2">
                                            {{ $customer->qualification ?? '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label font-weight-bold">Agencia de envío</label>
                                        <div class="form-control-plaintext border rounded bg-light px-3 py-2">
                                            {{ $customer->shipping_agency ?? '' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label font-weight-bold">Dirección de envío</label>
                                        <div class="form-control-plaintext border rounded bg-light px-3 py-2">
                                            {{ $customer->shipping_agency_address ?? '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label font-weight-bold">Fecha de registro</label>
                                    <div class="form-control-plaintext border rounded bg-light px-3 py-2">
                                        {{ $customer->created_at ? $customer->created_at->format('d/m/Y H:i') : '' }}
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('clientes.index') }}" class="btn btn-outline-primary">
                                        <i class="fa fa-arrow-left mr-1"></i> {{ __('dashboard.form.back to list') }}
                                    </a>
                                    <a href="{{ route('clientes.edit', [$customer->id]) }}" class="btn btn-success ml-2">
                                        <i class="fa fa-edit mr-1"></i> {{ __('dashboard.form.edit') }}
                                    </a>
                                </div>
                            </form>
                            @else
                            <div class="alert alert-danger mb-3">
                                Cliente no definido.
                            </div>
                            <a href="{{ route('clientes.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection