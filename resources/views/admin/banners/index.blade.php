@extends('dashboard.base')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Banners</h1>
        <a href="{{ route('banners.create') }}" class="btn btn-primary">Crear Banner</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Subtítulo</th>
                <th>Texto Botón</th>
                <th>URL Botón</th>
                <th>Orden</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($banners as $banner)
                <tr>
                    <td>{{ $banner->id }}</td>
                    <td>{{ $banner->title }}</td>
                    <td>{{ $banner->subtitle }}</td>
                    <td>{{ $banner->text_button }}</td>
                    <td>{{ $banner->url_button }}</td>
                    <td>{{ $banner->order }}</td>
                    <td><img src="{{ $banner->image_url }}" alt="Banner" style="max-width:120px;"></td>
                    <td>
                        <a href="{{ route('banners.edit', $banner) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('banners.destroy', $banner) }}" method="POST" style="display:inline-block;">
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
