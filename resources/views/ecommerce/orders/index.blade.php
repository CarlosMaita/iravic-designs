@extends('ecommerce.base')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mis Órdenes</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0">Mis Órdenes</h1>
            </div>

            @if($orders->count() > 0)
                @foreach($orders as $order)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5 class="card-title">Orden #{{ $order->id }}</h5>
                                    <p class="card-text text-muted">
                                        <i class="ci-calendar me-2"></i>{{ $order->date->format('d/m/Y H:i') }}
                                    </p>
                                    <p class="card-text">
                                        <span class="badge bg-{{ $order->status == 'creada' ? 'secondary' : ($order->status == 'pagada' ? 'info' : ($order->status == 'enviada' ? 'warning' : ($order->status == 'completada' ? 'success' : 'danger'))) }}">
                                            {{ $order->status_label }}
                                        </span>
                                    </p>
                                    <p class="card-text">
                                        <strong>Total:</strong> ${{ number_format($order->total, 2) }}<br>
                                        <strong>Pagado:</strong> ${{ number_format($order->total_paid, 2) }}<br>
                                        @if($order->remaining_balance > 0)
                                            <strong>Pendiente:</strong> ${{ number_format($order->remaining_balance, 2) }}
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-outline-primary">
                                        Ver Detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ci-cart h1 text-muted"></i>
                    <h5 class="mt-3">No tienes órdenes aún</h5>
                    <p class="text-muted">¡Explora nuestra tienda y realiza tu primera compra!</p>
                    <a href="{{ route('ecommerce.catalog') }}" class="btn btn-primary">
                        Ir a la Tienda
                    </a>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Resumen de Compras</h5>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span>Total de Órdenes:</span>
                        <span class="fw-bold">{{ $orders->total() }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Esta Página:</span>
                        <span>{{ $orders->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection