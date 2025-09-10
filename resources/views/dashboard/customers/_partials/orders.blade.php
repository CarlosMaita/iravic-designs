<div class="tab-pane fade @if($showOrdersTab) show active @endif" id="orders" role="tabpanel" aria-labelledby="orders-tab">
    <div class="row mt-3">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Esta sección muestra los pedidos del cliente. 
            </div>
            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id ?? 'N/A' }}</td>
                                <td>{{ $order->date ? $order->date->format('d/m/Y') : 'N/A' }}</td>
                                <td>{{ $order->total ?? 'N/A' }}</td>
                                <td>{{ $order->status ?? 'Pendiente' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Este cliente no tiene pedidos registrados.
                </div>
            @endif
        </div>
    </div>
</div>