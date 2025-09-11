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
                            
                            @php
                                $prices = $order->getPricesBothCurrencies();
                            @endphp
                            
                            <p class="mb-1">
                                <strong>Total:</strong> 
                                <span id="order-total-display">{{ $prices['usd']['formatted'] }}</span>
                                <small class="text-muted d-none" id="order-total-ves">
                                    ({{ $prices['ves']['formatted'] }})
                                </small>
                            </p>
                            
                            <p class="mb-1">
                                <strong>Pagado:</strong> 
                                <span data-usd-price="{{ $order->total_paid }}">${{ number_format($order->total_paid, 2) }}</span>
                            </p>
                            
                            @if($order->remaining_balance > 0)
                                <p class="mb-1">
                                    <strong>Pendiente:</strong> 
                                    <span data-usd-price="{{ $order->remaining_balance }}">${{ number_format($order->remaining_balance, 2) }}</span>
                                </p>
                            @endif
                            
                            @if($order->exchange_rate)
                                <p class="mb-1">
                                    <small class="text-muted">
                                        <strong>Tasa usada:</strong> {{ number_format($order->exchange_rate, 4, ',', '.') }} Bs/$
                                        <br><em>{{ $order->isPaid() ? 'Tasa al momento del pago' : 'Tasa actual' }}</em>
                                    </small>
                                </p>
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
                        <button class="btn btn-primary w-100" onclick="showPaymentModal()">Registrar Pago</button>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Acciones</h5>
                </div>
                <div class="card-body">
                    @if($order->canBeCancelled())
                        <button class="btn btn-danger w-100 mb-2" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                            <i class="ci-close me-2"></i>Cancelar Orden
                        </button>
                    @endif
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
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">Registrar Pago</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="paymentForm">
        <div class="modal-body">
          <div class="mb-3">
            <label for="amount" class="form-label">Monto a Pagar</label>
            <input type="number" class="form-control" id="amount" name="amount" min="0.01" step="0.01" required>
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
          <div class="mb-3" id="referenceField" style="display:none;">
            <label for="reference_number" class="form-label">Número de Referencia</label>
            <input type="text" class="form-control" id="reference_number" name="reference_number">
          </div>
          <div class="mb-3" id="dateField" style="display:none;">
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

@push('scripts')
<script>
(function(){
  function qs(id){ return document.getElementById(id); }
  window.showPaymentModal = function(){
    var el = qs('paymentModal');
    if(el && window.bootstrap){ new bootstrap.Modal(el).show(); }
  };
  var methodSel = qs('payment_method');
  if(methodSel){
    methodSel.addEventListener('change', function(){
      var isMobile = this.value === 'pago_movil';
      var refF = qs('referenceField'); var dateF = qs('dateField');
      var refI = qs('reference_number'); var dateI = qs('mobile_payment_date');
      if(refF) refF.style.display = isMobile ? 'block':'none';
      if(dateF) dateF.style.display = isMobile ? 'block':'none';
      if(refI) refI.required = isMobile;
      if(dateI) dateI.required = isMobile;
    });
  }
  var form = qs('paymentForm');
  if(form){
    form.addEventListener('submit', function(e){
      e.preventDefault();
      var data = Object.fromEntries(new FormData(form).entries());
      fetch('{{ route('customer.orders.add_payment', $order->id) }}', {
        method: 'POST',
        headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}' },
        body: JSON.stringify(data)
      })
      .then(r=>r.json())
      .then(res=>{ alert(res.message || 'Operación realizada'); if(res.success) location.reload(); })
      .catch(err=>{ console.error(err); alert('Error al procesar el pago.'); });
    });
  }
})();
</script>
@endpush
@endif

@if($order->canBeCancelled())
<!-- Cancel Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title" id="cancelOrderModalLabel">Confirmar Cancelación</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
    <div class="modal-body">
      <p>¿Está seguro que desea cancelar la orden <strong>#{{ $order->id }}</strong>? Esta acción no se puede deshacer.</p>
      <p class="small text-muted mb-2">Los productos volverán al inventario disponible si corresponde.</p>
      <div id="cancelOrderFeedback" class="d-none alert" role="alert"></div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      <button type="button" id="confirmCancelBtn" class="btn btn-danger">
        <span class="default-text">Sí, cancelar</span>
        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
      </button>
    </div>
  </div></div>
</div>

@push('scripts')
<script>
// Currency switching for order details
(function() {
    const exchangeRate = {{ $order->getEffectiveExchangeRate() }};
    const orderTotal = {{ $order->total }};
    const vesTotal = orderTotal * exchangeRate;
    
    function updateOrderCurrencyDisplay() {
        const currentCurrency = localStorage.getItem('selectedCurrency') || 'USD';
        const totalDisplay = document.getElementById('order-total-display');
        const vesDisplay = document.getElementById('order-total-ves');
        
        if (totalDisplay && vesDisplay) {
            if (currentCurrency === 'VES') {
                totalDisplay.textContent = 'Bs. ' + formatNumber(vesTotal);
                vesDisplay.textContent = '($' + formatNumber(orderTotal) + ')';
                vesDisplay.classList.remove('d-none');
            } else {
                totalDisplay.textContent = '$' + formatNumber(orderTotal);
                vesDisplay.textContent = '(Bs. ' + formatNumber(vesTotal) + ')';
                vesDisplay.classList.remove('d-none');
            }
        }
    }
    
    function formatNumber(number) {
        return new Intl.NumberFormat('es-VE', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(number);
    }
    
    // Listen for currency changes
    window.addEventListener('currency-changed', updateOrderCurrencyDisplay);
    
    // Initial load
    document.addEventListener('DOMContentLoaded', updateOrderCurrencyDisplay);
})();

@if($order->canBeCancelled())
(function(){
  var btn = document.getElementById('confirmCancelBtn');
  if(!btn) return; var fb = document.getElementById('cancelOrderFeedback');
  function msg(t,m){ if(!fb) return; fb.className='alert alert-'+t; fb.textContent=m; fb.classList.remove('d-none'); }
  function reset(){ btn.disabled=false; var sp=btn.querySelector('.spinner-border'); var tx=btn.querySelector('.default-text'); if(sp) sp.classList.add('d-none'); if(tx) tx.textContent='Sí, cancelar'; }
  btn.addEventListener('click', function(){ if(btn.disabled) return; btn.disabled=true; var sp=btn.querySelector('.spinner-border'); var tx=btn.querySelector('.default-text'); if(sp) sp.classList.remove('d-none'); if(tx) tx.textContent='Cancelando...';
    fetch('{{ route('customer.orders.cancel', $order->id) }}', { method:'POST', headers:{ 'Content-Type':'application/json','Accept':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'{{ csrf_token() }}' }, body: JSON.stringify({_token:'{{ csrf_token() }}'}) })
    .then(async r=>{ const ct=r.headers.get('Content-Type')||''; let d; try{ d=ct.includes('application/json')?await r.json():{success:r.ok,message:(await r.text()).trim()}; }catch{ d={success:false,message:'Respuesta inválida.'}; } if(d.success){ msg('success', d.message||'Orden cancelada'); setTimeout(()=>location.reload(),600);} else { msg('danger', d.message||'No se pudo cancelar.'); reset(); } })
    .catch(e=>{ console.error(e); msg('danger','Error al cancelar la orden.'); reset(); });
  });
})();
@endif
</script>
@endpush
@endsection