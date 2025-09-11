<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Payment Cards - Iravic Designs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .border-left-warning {
            border-left: 4px solid #ffc107 !important;
        }
        .border-left-success {
            border-left: 4px solid #198754 !important;
        }
        .border-left-danger {
            border-left: 4px solid #dc3545 !important;
        }
        .text-sm {
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">Demostración: Tarjetas Informativas de Pagos</h1>
        
        <!-- Simulated Order Info -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="card-title mb-0">Orden #12345</h4>
                    <span class="badge bg-secondary fs-6">Creada</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Información General</h6>
                        <p class="mb-1"><strong>Fecha:</strong> 11/09/2025 15:30</p>
                        <p class="mb-1"><strong>Total:</strong> $100.00</p>
                        <p class="mb-1"><strong>Pagado:</strong> $80.00</p>
                        <p class="mb-1"><strong>Pendiente:</strong> $20.00</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Información de Envío</h6>
                        <p class="mb-1"><strong>Nombre:</strong> Test Customer</p>
                        <p class="mb-1"><strong>Cédula:</strong> 12345678</p>
                        <p class="mb-1"><strong>Teléfono:</strong> +1234567890</p>
                        <p class="mb-1"><strong>Agencia:</strong> MRW</p>
                        <p class="mb-1"><strong>Dirección:</strong> Test Address 123</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- NEW Payment Cards Section -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Pagos Reportados</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Payment Card 1 - Pending -->
                    <div class="col-lg-6 mb-3">
                        <div class="card border-left-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0">Pago #1</h6>
                                    <span class="badge bg-warning">Pendiente</span>
                                </div>
                                
                                <div class="row text-sm">
                                    <div class="col-6">
                                        <p class="mb-1"><strong>Monto:</strong></p>
                                        <p class="text-primary fw-bold">$50.00</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1"><strong>Método:</strong></p>
                                        <p class="mb-2">Pago Móvil</p>
                                    </div>
                                </div>
                                
                                <div class="mb-2">
                                    <p class="mb-1"><strong>Referencia:</strong></p>
                                    <p class="text-muted">123456789</p>
                                </div>
                                
                                <div class="text-muted">
                                    <small><i class="ci-calendar me-1"></i>11/09/2025 14:30</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Card 2 - Verified -->
                    <div class="col-lg-6 mb-3">
                        <div class="card border-left-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0">Pago #2</h6>
                                    <span class="badge bg-success">Verificado</span>
                                </div>
                                
                                <div class="row text-sm">
                                    <div class="col-6">
                                        <p class="mb-1"><strong>Monto:</strong></p>
                                        <p class="text-primary fw-bold">$30.00</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1"><strong>Método:</strong></p>
                                        <p class="mb-2">Transferencia</p>
                                    </div>
                                </div>
                                
                                <div class="mb-2">
                                    <p class="mb-1"><strong>Referencia:</strong></p>
                                    <p class="text-muted">987654321</p>
                                </div>
                                
                                <div class="text-muted">
                                    <small><i class="ci-calendar me-1"></i>10/09/2025 16:45</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Card 3 - Rejected -->
                    <div class="col-lg-6 mb-3">
                        <div class="card border-left-danger">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0">Pago #3</h6>
                                    <span class="badge bg-danger">Rechazado</span>
                                </div>
                                
                                <div class="row text-sm">
                                    <div class="col-6">
                                        <p class="mb-1"><strong>Monto:</strong></p>
                                        <p class="text-primary fw-bold">$20.00</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1"><strong>Método:</strong></p>
                                        <p class="mb-2">Efectivo</p>
                                    </div>
                                </div>
                                
                                <div class="text-muted">
                                    <small><i class="ci-calendar me-1"></i>09/09/2025 10:20</small>
                                </div>
                                
                                <div class="mt-2">
                                    <p class="mb-1"><strong>Comentario:</strong></p>
                                    <p class="text-muted small">Pago rechazado por documentación incompleta</p>
                                </div>
                                
                                <div class="alert alert-warning mt-2 py-2">
                                    <small><i class="ci-info-circle me-1"></i>Este pago fue rechazado. Si considera que es un error, por favor contacte a nuestro equipo de soporte.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Card 4 - Another Pending -->
                    <div class="col-lg-6 mb-3">
                        <div class="card border-left-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0">Pago #4</h6>
                                    <span class="badge bg-warning">Pendiente</span>
                                </div>
                                
                                <div class="row text-sm">
                                    <div class="col-6">
                                        <p class="mb-1"><strong>Monto:</strong></p>
                                        <p class="text-primary fw-bold">$25.00</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1"><strong>Método:</strong></p>
                                        <p class="mb-2">Tarjeta</p>
                                    </div>
                                </div>
                                
                                <div class="mb-2">
                                    <p class="mb-1"><strong>Referencia:</strong></p>
                                    <p class="text-muted">555444333</p>
                                </div>
                                
                                <div class="text-muted">
                                    <small><i class="ci-calendar me-1"></i>11/09/2025 09:15</small>
                                </div>
                                
                                <div class="mt-2">
                                    <p class="mb-1"><strong>Comentario:</strong></p>
                                    <p class="text-muted small">Pago realizado con tarjeta de crédito terminada en 1234</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comparison: Old vs New -->
        <div class="mt-5">
            <h2 class="mb-3">Comparación: Antes vs Después</h2>
            
            <h5>Antes (formato de tabla):</h5>
            <div class="card mb-4">
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
                                <tr>
                                    <td>11/09/2025 14:30</td>
                                    <td>Pago Móvil</td>
                                    <td>$50.00</td>
                                    <td><span class="badge bg-warning">Pendiente</span></td>
                                    <td>123456789</td>
                                </tr>
                                <tr>
                                    <td>10/09/2025 16:45</td>
                                    <td>Transferencia</td>
                                    <td>$30.00</td>
                                    <td><span class="badge bg-success">Verificado</span></td>
                                    <td>987654321</td>
                                </tr>
                                <tr>
                                    <td>09/09/2025 10:20</td>
                                    <td>Efectivo</td>
                                    <td>$20.00</td>
                                    <td><span class="badge bg-danger">Rechazado</span></td>
                                    <td>N/A</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <h5>Después (formato de tarjetas informativas):</h5>
            <p class="text-muted">↑ Vea la sección "Pagos Reportados" arriba para la nueva presentación en tarjetas.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>