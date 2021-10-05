<div id="zone-{{$zone->id}}" class="list-group-item zone-item" data-id="{{ $zone->id }}">
    <h5 class="zone-name d-flex justify-content-between mb-0">
        <div>
            <button class="btn btn-move-zone" type="button"><i class="fas fa-expand-arrows-alt"></i></button>
            <span>{{ $zone->name }} @if ($customers_qty) <small class="qty-customers">({{ $customers_qty }}) clientes</small> @endif </span>
        </div>
        <div>
            <div class="btn-group dropleft">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Opciones
                </button>
                <div class="dropdown-menu py-0">
                    {{--  --}}
                    @can('view', $zone)
                        <a class="dropdown-item border-bottom" href="{{ route('zonas.show', $zone->id) }}">Ver</a>
                    @endcan
                    {{--  --}}
                    @can('update', $zone)
                        <a class="dropdown-item border-bottom" href="{{ route('zonas.edit', $zone->id) }}">Editar</a>
                    @endcan
                    {{--  --}}
                    @can('delete', $zone)
                        <button class="dropdown-item delete-zone" type="button" data-id="{{ $zone->id }}">Eliminar</button>
                    @endcan
                </div>
            </div>
        </div>  
    </h5>
</div>