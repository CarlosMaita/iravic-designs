@extends('dashboard.base')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>CTAs del Home</h1>
        <a href="{{ route('admin.home-ctas.create') }}" class="btn btn-primary">Crear CTA</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Icono</th>
                <th>Descripción</th>
                <th>Texto CTA</th>
                <th>URL CTA</th>
                <th>Orden</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($homeCtas as $homeCta)
                <tr>
                    <td>{{ $homeCta->id }}</td>
                    <td>{{ $homeCta->title }}</td>
                    <td>
                        @if($homeCta->icon)
                            <i class="{{ $homeCta->icon }} fa-2x"></i>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ Str::limit($homeCta->description, 50) }}</td>
                    <td>{{ $homeCta->cta_text }}</td>
                    <td>{{ $homeCta->cta_url }}</td>
                    <td>{{ $homeCta->order }}</td>
                    <td>
                        <a href="{{ route('admin.home-ctas.edit', $homeCta) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('admin.home-ctas.destroy', $homeCta) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
