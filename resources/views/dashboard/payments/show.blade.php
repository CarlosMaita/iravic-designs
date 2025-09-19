@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-credit-card"></i> Pago #{{ $payment->id }}
                            @if($payment->archived)
                                <span class="badge badge-secondary ml-2">ARCHIVADO</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Información del Cliente</h5>
                                    <p><strong>Nombre:</strong> {{ $payment->customer ? $payment->customer->name : 'N/A' }}</p>
                                    <p><strong>Teléfono:</strong> {{ $payment->customer ? $payment->customer->cellphone : 'N/A' }}</p>
                                    <p><strong>Email:</strong> {{ $payment->customer ? $payment->customer->email : 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Información del Pago</h5>
                                    <p><strong>Estado:</strong> 
                                        <span class="badge badge-{{ $payment->status == 'pendiente' ? 'warning' : ($payment->status == 'verificado' ? 'success' : 'danger') }}">
                                            {{ $payment->status_label }}
                                        </span>
                                    </p>
                                    <p><strong>Fecha:</strong> {{ $payment->date->format('d/m/Y H:i') }}</p>
                                    <p><strong>Monto:</strong> ${{ number_format($payment->amount, 2) }}</p>
                                    <p><strong>Método:</strong> {{ $payment->payment_method_label }}</p>
                                </div>
                            </div>
                            
                            @if($payment->order)
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Orden Relacionada</h5>
                                    <p><strong>Orden:</strong> 
                                        <a href="{{ route('admin.orders.show', $payment->order->id) }}">#{{ $payment->order->id }}</a>
                                    </p>
                                    <p><strong>Estado de Orden:</strong> 
                                        <span class="badge badge-{{ $payment->order->status == 'creada' ? 'secondary' : ($payment->order->status == 'pagada' ? 'info' : ($payment->order->status == 'enviada' ? 'warning' : ($payment->order->status == 'completada' ? 'success' : 'danger'))) }}">
                                            {{ $payment->order->status_label }}
                                        </span>
                                    </p>
                                    <p><strong>Total de Orden:</strong> ${{ number_format($payment->order->total, 2) }}</p>
                                </div>
                            </div>
                            @endif
                            
                            @if($payment->payment_method === 'pago_movil')
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Detalles del Pago Móvil</h5>
                                    <p><strong>Referencia:</strong> {{ $payment->reference_number }}</p>
                                    <p><strong>Primeros 6 dígitos:</strong> {{ $payment->reference_digits }}...</p>
                                    @if($payment->mobile_payment_date)
                                        <p><strong>Fecha del Pago:</strong> {{ $payment->mobile_payment_date->format('d/m/Y H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                            @endif
                            
                            @if($payment->comment)
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Comentarios</h5>
                                    <p>{{ $payment->comment }}</p>
                                </div>
                            </div>
                            @endif
                            
            @if($payment->archived)
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Este pago está archivado y no puede ser modificado.
            </div>
            @endif
            
            @if($payment->status === 'pendiente' && !$payment->archived)
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Acciones</h5>
                                    <button onclick="verifyPayment({{ $payment->id }})" class="btn btn-success">
                                        <i class="fa fa-check"></i> Verificar Pago
                                    </button>
                                    <button onclick="rejectPayment({{ $payment->id }})" class="btn btn-danger">
                                        <i class="fa fa-times"></i> Rechazar Pago
                                    </button>
                                </div>
                            </div>
                            @endif
                                                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    @include('plugins.sweetalert')
    <script>
        function verifyPayment(paymentId) {
            swal({
                title: '¿Verificar Pago?',
                text: "Confirme que ha validado la información del pago",
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, verificar',
                cancelButtonText: 'Cancelar'
            }).then(function () {
                $.ajax({
                    url: `/admin/pagos/${paymentId}/verify`,
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function() {
                        new Noty({
                            text: 'Error al verificar el pago',
                            type: 'error'
                        }).show();
                    }
                });
            }).catch(swal.noop);
        }

        function rejectPayment(paymentId) {
            swal({
                title: '¿Rechazar Pago?',
                text: "Esta acción marcará el pago como rechazado",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, rechazar',
                cancelButtonText: 'Cancelar'
            }).then(function () {
                $.ajax({
                    url: `/admin/pagos/${paymentId}/reject`,
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function() {
                        new Noty({
                            text: 'Error al rechazar el pago',
                            type: 'error'
                        }).show();
                    }
                });
            }).catch(swal.noop);
        }
    </script>
@endpush