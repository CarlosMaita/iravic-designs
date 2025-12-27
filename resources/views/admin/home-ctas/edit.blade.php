@extends('dashboard.base')

@section('content')
<div class="container">
    <h1>Editar CTA del Home</h1>
    <form action="{{ route('admin.home-ctas.update', $homeCta) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.home-ctas._form')
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('admin.home-ctas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
