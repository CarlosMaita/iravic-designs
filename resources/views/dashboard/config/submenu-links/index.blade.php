@extends('dashboard.base')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Enlaces del Submenú</h1>
        <a href="{{ route('admin.submenu-links.create') }}" class="btn btn-primary">Crear Enlace</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>URL</th>
                <th>Orden</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($submenuLinks as $link)
                <tr>
                    <td>{{ $link->id }}</td>
                    <td>{{ $link->title }}</td>
                    <td>{{ $link->url }}</td>
                    <td>{{ $link->order }}</td>
                    <td>
                        @if($link->active)
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-secondary">Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.submenu-links.edit', $link) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('admin.submenu-links.destroy', $link) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No hay enlaces creados</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
