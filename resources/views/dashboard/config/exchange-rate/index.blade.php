@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-dollar-sign"></i> Gestión de Tasa de Cambio USD/VES
                        </div>
                        <div class="card-body">
                            <!-- Información actual de la tasa -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h4 class="mb-0">{{ $rateInfo['formatted_rate'] }} VES</h4>
                                                    <small>por 1 USD</small>
                                                </div>
                                                <div class="text-right">
                                                    <i class="fa fa-exchange-alt fa-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-info text-white">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="mb-0">Última Actualización</h6>
                                                    <small>{{ $rateInfo['last_update_formatted'] }}</small>
                                                </div>
                                                <div class="text-right">
                                                    <i class="fa fa-clock fa-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actualización automática desde BCV -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0">
                                                <i class="fa fa-globe"></i> Actualización Automática desde BCV
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">
                                                La tasa de cambio se actualiza automáticamente cada hora desde el sitio web del 
                                                Banco Central de Venezuela (BCV). También puedes forzar una actualización manual.
                                            </p>
                                            
                                            @can('create', App\Models\Config::class)
                                                <form method="POST" action="{{ route('admin.exchange-rate.update-bcv') }}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fa fa-sync"></i> Actualizar desde BCV
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actualización manual -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0">
                                                <i class="fa fa-edit"></i> Actualización Manual
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">
                                                Si es necesario, puedes establecer la tasa de cambio manualmente. 
                                                Usa el formato decimal con punto (ej: 36.5678).
                                            </p>

                                            @can('create', App\Models\Config::class)
                                                <form method="POST" action="{{ route('admin.exchange-rate.update-manual') }}">
                                                    @csrf
                                                    <div class="form-group row">
                                                        <label for="exchange_rate" class="col-md-3 col-form-label">
                                                            Tasa de Cambio (VES por USD):
                                                        </label>
                                                        <div class="col-md-4">
                                                            <input 
                                                                type="number" 
                                                                step="0.0001" 
                                                                min="0.01" 
                                                                max="999999.9999"
                                                                class="form-control @error('exchange_rate') is-invalid @enderror" 
                                                                id="exchange_rate" 
                                                                name="exchange_rate" 
                                                                value="{{ old('exchange_rate', $rateInfo['rate']) }}"
                                                                placeholder="Ej: 36.5678"
                                                                required
                                                            >
                                                            @error('exchange_rate')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="submit" class="btn btn-warning">
                                                                <i class="fa fa-save"></i> Actualizar Manualmente
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Información adicional -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <h6><i class="fa fa-info-circle"></i> Información Importante:</h6>
                                        <ul class="mb-0">
                                            <li>La tasa de cambio se actualiza automáticamente cada hora desde el BCV</li>
                                            <li>Los precios de productos se muestran en USD por defecto</li>
                                            <li>Los clientes pueden cambiar la visualización a VES multiplicando por esta tasa</li>
                                            <li>Se conservan todos los decimales para máxima precisión</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Botón de regreso -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <a href="{{ route('general.index') }}" class="btn btn-secondary">
                                        <i class="fa fa-arrow-left"></i> Volver a Configuración General
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
// Auto-refresh rate info every 5 minutes
setInterval(function() {
    fetch('{{ route("admin.exchange-rate.current") }}')
        .then(response => response.json())
        .then(data => {
            // Update display if needed
            console.log('Current rate:', data.rate);
        })
        .catch(error => console.log('Error fetching rate:', error));
}, 300000); // 5 minutes
</script>
@endsection