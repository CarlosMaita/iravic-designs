@extends('dashboard.base')

@section('content')
<div class="container">
    <h1>Crear CTA del Home</h1>
    <form action="{{ route('admin.home-ctas.store') }}" method="POST">
        @csrf
        @include('admin.home-ctas._form')
        <button type="submit" class="btn btn-primary">Crear</button>
        <a href="{{ route('admin.home-ctas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
