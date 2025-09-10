<div class="tab-pane fade" id="account-status" role="tabpanel" aria-labelledby="account-status-tab">
    <div class="row mt-3">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                La gestión financiera y de estados de cuenta se ha simplificado. 
                El nuevo modelo de clientes se enfoca en la información de envío y contacto.
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Estado del Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Calificación</label>
                        <input class="form-control" type="text" value="{{ $customer->qualification }}" readOnly>
                    </div>
                    <div class="form-group">
                        <label>Estado de Información de Envío</label>
                        @if($customer->hasCompleteShippingInfo())
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check-circle"></i> Información completa
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-triangle"></i> 
                                Información incompleta
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Resumen</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Total de Pedidos</label>
                        <input class="form-control" type="text" value="{{ $customer->orders()->count() }}" readOnly>
                    </div>
                    <div class="form-group">
                        <label>Fecha de Registro</label>
                        <input class="form-control" type="text" value="{{ $customer->created_at ? $customer->created_at->format('d/m/Y') : 'N/A' }}" readOnly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>