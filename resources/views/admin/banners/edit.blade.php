@extends('dashboard.base')

@section('content')
<div class="container">
    <h1>Editar Banner</h1>
    <form action="{{ route('banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.banners._form')
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('banners.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
