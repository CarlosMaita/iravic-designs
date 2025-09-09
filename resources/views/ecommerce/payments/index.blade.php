@extends('ecommerce.base')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mis Pagos</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0">Mis Pagos</h1>
            </div>

            @if($payments->count() > 0)
                @foreach($payments as $payment)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5 class="card-title">Pago #{{ $payment->id }}</h5>
                                    <p class="card-text text-muted">
                                        <i class="ci-calendar me-2"></i>{{ $payment->date->format('d/m/Y H:i') }}
                                    </p>
                                    <p class="card-text">
                                        <span class="badge bg-{{ $payment->status == 'pendiente' ? 'warning' : ($payment->status == 'verificado' ? 'success' : 'danger') }}">
                                            {{ $payment->status_label }}
                                        </span>
                                    </p>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="card-text mb-1">
                                                <strong>Monto:</strong> ${{ number_format($payment->amount, 2) }}
                                            </p>
                                            <p class="card-text mb-1">
                                                <strong>Método:</strong> {{ $payment->payment_method_label }}
                                            </p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="card-text mb-1">
                                                <strong>Orden:</strong> 
                                                @if($payment->order)
                                                    <a href="{{ route('customer.orders.show', $payment->order->id) }}">#{{ $payment->order->id }}</a>
                                                @else
                                                    N/A
                                                @endif
                                            </p>
                                            @if($payment->reference_number)
                                                <p class="card-text mb-1">
                                                    <strong>Referencia:</strong> {{ $payment->reference_number }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    @if($payment->comment)
                                        <p class="card-text mt-2">
                                            <strong>Comentario:</strong> {{ $payment->comment }}
                                        </p>
                                    @endif
                                    @if($payment->status == 'rechazado')
                                        <div class="alert alert-warning mt-2">
                                            <small><i class="ci-info-circle me-1"></i>Este pago fue rechazado. Si considera que es un error, por favor contacte a nuestro equipo de soporte.</small>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4 text-end">
                                    @if($payment->order)
                                        <a href="{{ route('customer.orders.show', $payment->order->id) }}" class="btn btn-outline-primary">
                                            Ver Orden
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $payments->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ci-credit-card h1 text-muted"></i>
                    <h5 class="mt-3">No tienes pagos registrados</h5>
                    <p class="text-muted">¡Realiza tu primera compra y registra tus pagos aquí!</p>
                    <a href="{{ route('customer.orders.index') }}" class="btn btn-primary">
                        Ver Mis Órdenes
                    </a>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Estado de Pagos</h5>
                    <hr>
                    @php
                        $pendingCount = $payments->where('status', 'pendiente')->count();
                        $verifiedCount = $payments->where('status', 'verificado')->count();
                        $rejectedCount = $payments->where('status', 'rechazado')->count();
                    @endphp
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total de Pagos:</span>
                        <span class="fw-bold">{{ $payments->total() }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Pendientes:</span>
                        <span class="badge bg-warning">{{ $pendingCount }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Verificados:</span>
                        <span class="badge bg-success">{{ $verifiedCount }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Rechazados:</span>
                        <span class="badge bg-danger">{{ $rejectedCount }}</span>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Acciones</h5>
                    <hr>
                    <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="ci-cart me-2"></i>Ver Mis Órdenes
                    </a>
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary w-100">
                        <i class="ci-home me-2"></i>Ir al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection