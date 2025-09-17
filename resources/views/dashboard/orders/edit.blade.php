@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-edit"></i> Editar Orden #{{ $order->id }}
                        </div>
                        <div class="card-body">
                            @php($showExchange = old('status', $order->status) === 'pagada')
                            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <fieldset class="mb-4">
                                    <legend class="h6 text-uppercase text-muted mb-3">Estado y Pago</legend>
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label for="status">Estado de la Orden</label>
                                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                                    @foreach($statuses as $key => $label)
                                                        <option value="{{ $key }}" {{ old('status', $order->status) == $key ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="form-group {{ $showExchange ? '' : 'd-none' }}" id="exchange-rate-group">
                                                <label for="exchange_rate">Tasa de Cambio (VES por USD)</label>
                                                <div class="input-group">
                                                    <input type="number"
                                                           name="exchange_rate"
                                                           id="exchange_rate"
                                                           class="form-control @error('exchange_rate') is-invalid @enderror"
                                                           value="{{ old('exchange_rate', $order->exchange_rate ?: \App\Helpers\CurrencyHelper::getCurrentExchangeRate()) }}"
                                                           step="0.0001"
                                                           min="0"
                                                           placeholder="Ej: 365.2500"
                                                           {{ $showExchange ? 'required' : '' }}>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Bs/$</span>
                                                    </div>
                                                </div>
                                                <small class="form-text text-muted">
                                                    Tasa de cambio utilizada al momento del pago.
                                                    <span id="current-rate">Tasa actual: {{ number_format(\App\Helpers\CurrencyHelper::getCurrentExchangeRate(), 4, ',', '.') }} Bs/$</span>
                                                </small>
                                                @error('exchange_rate')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <fieldset class="mb-4">
                                    <legend class="h6 text-uppercase text-muted mb-3">Envío</legend>
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label for="shipping_agency">Agencia de Envío</label>
                                                <select name="shipping_agency" id="shipping_agency" class="form-control @error('shipping_agency') is-invalid @enderror">
                                                    <option value="">Seleccionar agencia</option>
                                                    @foreach($shippingAgencies as $agency)
                                                        <option value="{{ $agency }}" {{ old('shipping_agency', $order->shipping_agency) == $agency ? 'selected' : '' }}>
                                                            {{ $agency }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('shipping_agency')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label for="shipping_tracking_number">Número de Guía</label>
                                                <input type="text"
                                                       name="shipping_tracking_number"
                                                       id="shipping_tracking_number"
                                                       class="form-control @error('shipping_tracking_number') is-invalid @enderror"
                                                       value="{{ old('shipping_tracking_number', $order->shipping_tracking_number) }}"
                                                       placeholder="Ej: 1234567890">
                                                @error('shipping_tracking_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <div class="d-flex">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-save"></i> Actualizar Orden
                                    </button>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary ml-2">
                                        <i class="fa fa-arrow-left"></i> Volver
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const exchangeRateGroup = document.getElementById('exchange-rate-group');
    const exchangeRateInput = document.getElementById('exchange_rate');
    
    function toggleExchangeRateField() {
        const selectedStatus = statusSelect.value;
        const isPaid = selectedStatus === 'pagada';
        exchangeRateGroup.classList.toggle('d-none', !isPaid);
        exchangeRateInput.required = isPaid;
    }
    
    // Initial check
    toggleExchangeRateField();
    
    // Listen for status changes
    statusSelect.addEventListener('change', toggleExchangeRateField);
});
</script>
@endpush