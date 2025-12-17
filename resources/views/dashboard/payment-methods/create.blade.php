@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-8 col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-credit-card"></i> Crear Método de Pago
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.payment-methods.store') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="name">Nombre <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Ej: Pago Móvil, Binance, PayPal"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="code">Código <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('code') is-invalid @enderror" 
                                           id="code" 
                                           name="code" 
                                           value="{{ old('code') }}" 
                                           placeholder="Ej: pago_movil, binance, paypal"
                                           required>
                                    <small class="form-text text-muted">
                                        Use solo letras minúsculas, números y guiones bajos. Debe ser único.
                                    </small>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="instructions">Instrucciones</label>
                                    <textarea class="form-control @error('instructions') is-invalid @enderror" 
                                              id="instructions" 
                                              name="instructions" 
                                              rows="4"
                                              placeholder="Ej: Realizar pago móvil al número 0414-1234567, Banco Mercantil, V-12345678">{{ old('instructions') }}</textarea>
                                    <small class="form-text text-muted">
                                        Estas instrucciones se mostrarán al cliente al seleccionar este método de pago.
                                    </small>
                                    @error('instructions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="sort_order">Orden de Visualización</label>
                                    <input type="number" 
                                           class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" 
                                           name="sort_order" 
                                           value="{{ old('sort_order', 0) }}" 
                                           min="0">
                                    <small class="form-text text-muted">
                                        Menor número aparece primero. Use 0 para el orden por defecto.
                                    </small>
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1"
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Método de pago activo
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Solo los métodos activos estarán disponibles para los clientes.
                                    </small>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-save"></i> Guardar
                                    </button>
                                    <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-secondary">
                                        <i class="fa fa-arrow-left"></i> Cancelar
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
