@extends('ecommerce.base')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.orders.index') }}">Mis Órdenes</a></li>
            <li class="breadcrumb-item active" aria-current="page">Orden #{{ $order->id }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title mb-0">Orden #{{ $order->id }}</h4>
                        <span class="badge bg-{{ $order->status == 'creada' ? 'secondary' : ($order->status == 'pagada' ? 'info' : ($order->status == 'enviada' ? 'warning' : ($order->status == 'completada' ? 'success' : 'danger'))) }} fs-6">
                            {{ $order->status_label }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Información General</h6>
                            <p class="mb-1"><strong>Fecha:</strong> {{ $order->date->format('d/m/Y H:i') }}</p>
                            <p class="mb-1"><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
                            <p class="mb-1"><strong>Pagado:</strong> ${{ number_format($order->total_paid, 2) }}</p>
                            @if($order->remaining_balance > 0)
                                <p class="mb-1"><strong>Pendiente:</strong> ${{ number_format($order->remaining_balance, 2) }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6>Información de Envío</h6>
                            <p class="mb-1"><strong>Nombre:</strong> {{ $order->shipping_name }}</p>
                            <p class="mb-1"><strong>Cédula:</strong> {{ $order->shipping_dni }}</p>
                            <p class="mb-1"><strong>Teléfono:</strong> {{ $order->shipping_phone }}</p>
                            <p class="mb-1"><strong>Agencia:</strong> {{ $order->shipping_agency }}</p>
                            <p class="mb-1"><strong>Dirección:</strong> {{ $order->shipping_address }}</p>
                            @if($order->shipping_tracking_number)
                                <p class="mb-1"><strong>Número de Guía:</strong> {{ $order->shipping_tracking_number }}</p>
                            @endif
                        </div>
                    </div>

                    <h6>Productos</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio Unit.</th>
                                    <th>Cantidad</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderProducts as $orderProduct)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($orderProduct->product && $orderProduct->product->cover)
                                                    <img src="{{ asset('storage/' . $orderProduct->product->cover) }}" alt="{{ $orderProduct->product_name }}" class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $orderProduct->product_name }}</h6>
                                                    @if($orderProduct->color_id || $orderProduct->size_id)
                                                        <small class="text-muted">
                                                            @if($orderProduct->color_id)
                                                                Color: {{ $orderProduct->color->name ?? 'N/A' }}
                                                            @endif
                                                            @if($orderProduct->size_id)
                                                                | Talla: {{ $orderProduct->size->name ?? 'N/A' }}
                                                            @endif
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>${{ number_format($orderProduct->product_price, 2) }}</td>
                                        <td>{{ $orderProduct->qty }}</td>
                                        <td><strong>${{ number_format($orderProduct->total, 2) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th>${{ number_format($order->total, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            @if($order->payments->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Historial de Pagos</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Método</th>
                                        <th>Monto</th>
                                        <th>Estado</th>
                                        <th>Referencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->date->format('d/m/Y H:i') }}</td>
                                            <td>{{ $payment->payment_method_label }}</td>
                                            <td>${{ number_format($payment->amount, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $payment->status == 'pendiente' ? 'warning' : ($payment->status == 'verificado' ? 'success' : 'danger') }}">
                                                    {{ $payment->status_label }}
                                                </span>
                                            </td>
                                            <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            @if($order->canBePaid() && $order->remaining_balance > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Realizar Pago</h5>
                    </div>
                    <div class="card-body">
                        <p>Monto pendiente: <strong>${{ number_format($order->remaining_balance, 2) }}</strong></p>
                        <button class="btn btn-primary w-100" onclick="showPaymentModal()">
                            Registrar Pago
                        </button>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Acciones</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="ci-arrow-left me-2"></i>Volver a Órdenes
                    </a>
                    @if($order->status == 'completada')
                        <button class="btn btn-outline-primary w-100" onclick="window.print()">
                            <i class="ci-download me-2"></i>Imprimir Orden
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($order->canBePaid() && $order->remaining_balance > 0)
<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Registrar Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="paymentForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Monto a Pagar</label>
                        <input type="number" class="form-control" id="amount" name="amount" min="0.01" max="{{ $order->remaining_balance }}" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Método de Pago</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="">Seleccione un método</option>
                            <option value="pago_movil">Pago Móvil</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="efectivo">Efectivo</option>
                        </select>
                    </div>
                    <div class="mb-3" id="referenceField" style="display: none;">
                        <label for="reference_number" class="form-label">Número de Referencia</label>
                        <input type="text" class="form-control" id="reference_number" name="reference_number">
                    </div>
                    <div class="mb-3" id="dateField" style="display: none;">
                        <label for="mobile_payment_date" class="form-label">Fecha del Pago</label>
                        <input type="datetime-local" class="form-control" id="mobile_payment_date" name="mobile_payment_date">
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Comentarios (Opcional)</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar Pago</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showPaymentModal() {
    new bootstrap.Modal(document.getElementById('paymentModal')).show();
}

document.getElementById('payment_method').addEventListener('change', function() {
    const referenceField = document.getElementById('referenceField');
    const dateField = document.getElementById('dateField');
    
    if (this.value === 'pago_movil') {
        referenceField.style.display = 'block';
        dateField.style.display = 'block';
        document.getElementById('reference_number').required = true;
        document.getElementById('mobile_payment_date').required = true;
    } else {
        referenceField.style.display = 'none';
        dateField.style.display = 'none';
        document.getElementById('reference_number').required = false;
        document.getElementById('mobile_payment_date').required = false;
    }
});

document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    fetch('{{ route("customer.orders.add_payment", $order->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert(result.message);
            location.reload();
        } else {
            alert(result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar el pago. Intente nuevamente.');
    });
});
</script>
@endif
@endsection