@extends('dashboard.base')

@section('content')
<div class="container-fluid">
    <div class="fade-in">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <i class="fa fa-star"></i> Ofertas Especiales
                        <a href="{{ route('special-offers.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Nueva Oferta
                        </a>
                    </div>
                    <div class="card-body">
                        @include('dashboard.shared.flash-message')
                        
                        @if($specialOffers->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Título</th>
                                            <th>Producto</th>
                                            <th>Descuento</th>
                                            <th>Inicio</th>
                                            <th>Fin</th>
                                            <th>Estado</th>
                                            <th>Orden</th>
                                            <th>Imagen</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($specialOffers as $offer)
                                            <tr>
                                                <td>{{ $offer->id }}</td>
                                                <td>{{ $offer->title }}</td>
                                                <td>{{ $offer->product->name ?? 'N/A' }}</td>
                                                <td>
                                                    @if($offer->discount_percentage)
                                                        {{ number_format($offer->discount_percentage, 0) }}%
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{ $offer->start_date->format('d/m/Y') }}</td>
                                                <td>{{ $offer->end_date->format('d/m/Y') }}</td>
                                                <td>
                                                    @if($offer->is_current)
                                                        <span class="badge badge-success">Activa</span>
                                                    @elseif($offer->is_active)
                                                        <span class="badge badge-warning">Programada</span>
                                                    @else
                                                        <span class="badge badge-secondary">Inactiva</span>
                                                    @endif
                                                </td>
                                                <td>{{ $offer->order }}</td>
                                                <td>
                                                    @if($offer->image)
                                                        <img src="{{ $offer->image_url }}" alt="Oferta" style="max-width: 80px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <span class="text-muted">Sin imagen</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('special-offers.edit', $offer) }}" class="btn btn-sm btn-warning" title="Editar">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('special-offers.destroy', $offer) }}" method="POST" style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                                    onclick="return confirm('¿Está seguro de eliminar esta oferta especial?')" 
                                                                    title="Eliminar">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="d-flex justify-content-center">
                                {{ $specialOffers->links() }}
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-muted">No hay ofertas especiales registradas.</p>
                                <a href="{{ route('special-offers.create') }}" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> Crear Primera Oferta
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection