@extends('dashboard.base')

@section('content')
<div class="container">
    <h1>Editar Color</h1>
    <form action="{{ route('colors.update', $color->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $color->name }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="code">Código Hexadecimal</label>
                    <input type="color" name="code" id="code" class="form-control" value="{{ $color->code }}" required>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
    </form>
</div>
@endsection
