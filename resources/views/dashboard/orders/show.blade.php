@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-shopping-cart"></i> Orden #{{ $order->id }}
                            <div class="card-header-actions">
                                                                    <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-sm btn-success">
                                        <i class="fa fa-edit"></i> Editar
                                    </a>
                                                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Información del Cliente</h5>
                                    <p><strong>Nombre:</strong> {{ $order->customer ? $order->customer->name : 'N/A' }}</p>
                                    <p><strong>Teléfono:</strong> {{ $order->customer ? $order->customer->cellphone : 'N/A' }}</p>
                                    <p><strong>Email:</strong> {{ $order->customer ? $order->customer->email : 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Información de la Orden</h5>
                                    <p><strong>Estado:</strong> 
                                        <span class="badge badge-{{ $order->status == 'creada' ? 'secondary' : ($order->status == 'pagada' ? 'info' : ($order->status == 'enviada' ? 'warning' : ($order->status == 'completada' ? 'success' : 'danger'))) }}">
                                            {{ $order->status_label }}
                                        </span>
                                    </p>
                                    <p><strong>Fecha:</strong> {{ $order->date->format('d/m/Y H:i') }}</p>
                                    <p><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Información de Envío</h5>
                                    <p><strong>Nombre:</strong> {{ $order->shipping_name }}</p>
                                    <p><strong>Cédula:</strong> {{ $order->shipping_dni }}</p>
                                    <p><strong>Teléfono:</strong> {{ $order->shipping_phone }}</p>
                                    <p><strong>Agencia:</strong> {{ $order->shipping_agency }}</p>
                                    <p><strong>Dirección:</strong> {{ $order->shipping_address }}</p>
                                    @if($order->shipping_tracking_number)
                                        <p><strong>Número de Guía:</strong> {{ $order->shipping_tracking_number }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h5>Estado de Pagos</h5>
                                    <p><strong>Total Pagado:</strong> ${{ number_format($order->total_paid, 2) }}</p>
                                    <p><strong>Saldo Pendiente:</strong> ${{ number_format($order->remaining_balance, 2) }}</p>
                                    @if($order->is_fully_paid)
                                        <span class="badge badge-success">Completamente Pagado</span>
                                    @else
                                        <span class="badge badge-warning">Pago Pendiente</span>
                                    @endif
                                </div>
                            </div>
                            
                            <hr>
                            
                            <h5>Productos</h5>
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
                                                <td>{{ $orderProduct->product_name }}</td>
                                                <td>${{ number_format($orderProduct->product_price, 2) }}</td>
                                                <td>{{ $orderProduct->qty }}</td>
                                                <td>${{ number_format($orderProduct->total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3">Total</th>
                                            <th>${{ number_format($order->total, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            
                            <hr>
                            
                            <h5>Pagos Registrados</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Monto</th>
                                            <th>Método</th>
                                            <th>Referencia</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($order->payments as $payment)
                                            <tr>
                                                <td>{{ $payment->date->format('d/m/Y H:i') }}</td>
                                                <td>${{ number_format($payment->amount, 2) }}</td>
                                                <td>{{ $payment->payment_method_label }}</td>
                                                <td>{{ $payment->reference_digits }}...</td>
                                                <td>
                                                    <span class="badge badge-{{ $payment->status == 'pendiente' ? 'warning' : ($payment->status == 'verificado' ? 'success' : 'danger') }}">
                                                        {{ $payment->status_label }}
                                                    </span>
                                                </td>
                                                <td>
                                                                                                            <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-sm btn-primary">Ver</a>
                                                                                                    </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No hay pagos registrados</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection