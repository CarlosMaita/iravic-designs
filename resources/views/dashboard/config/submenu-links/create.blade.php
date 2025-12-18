@extends('dashboard.base')

@section('content')
<div class="container">
    <h1>Crear Enlace de Submenú</h1>
    <form action="{{ route('admin.submenu-links.store') }}" method="POST">
        @csrf
        @include('dashboard.config.submenu-links._form')
        <div class="mt-3">
            <a href="{{ route('admin.submenu-links.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Crear</button>
        </div>
    </form>
</div>
@endsection
