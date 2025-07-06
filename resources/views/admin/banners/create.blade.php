@extends('dashboard.base')

@section('content')
<div class="container">
    <h1>Crear Banner</h1>
    <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.banners._form')
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('banners.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
