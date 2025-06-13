@extends('dashboard.base')

@section('content')
<div class="container">
    <h1>Colores</h1>
    <div class="justify-end"> 
        <a href="{{ route('colors.create') }}" class="btn btn-primary">Agregar Color</a>
    </div>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Color / Código</th>
                <th>Cantidad de Productos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($colors as $color)
                <tr>
                    <td>{{ $color->name }}</td>
                    <td><div style="width: 20px; height: 20px; border-radius: 50%; border: 1px solid gray; background-color: {{ $color->code }}; display: inline-block;"></div> / {{ $color->code }}</td>
                    <td>{{ $color->products_count }}</td>
                    <td>
                        <a href="{{ route('colors.edit', $color->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('colors.destroy', $color->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
