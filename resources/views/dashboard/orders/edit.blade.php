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
                            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Estado de la Orden</label>
                                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                                @foreach($statuses as $key => $label)
                                                    <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="shipping_agency">Agencia de Envío</label>
                                            <select name="shipping_agency" id="shipping_agency" class="form-control @error('shipping_agency') is-invalid @enderror">
                                                <option value="">Seleccionar agencia</option>
                                                @foreach($shippingAgencies as $agency)
                                                    <option value="{{ $agency }}" {{ $order->shipping_agency == $agency ? 'selected' : '' }}>
                                                        {{ $agency }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('shipping_agency')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="shipping_tracking_number">Número de Guía</label>
                                            <input type="text" 
                                                   name="shipping_tracking_number" 
                                                   id="shipping_tracking_number" 
                                                   class="form-control @error('shipping_tracking_number') is-invalid @enderror"
                                                   value="{{ old('shipping_tracking_number', $order->shipping_tracking_number) }}"
                                                   placeholder="Ingrese el número de guía">
                                            @error('shipping_tracking_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-save"></i> Actualizar Orden
                                    </button>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary">
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