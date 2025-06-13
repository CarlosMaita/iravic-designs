@extends('dashboard.base')

@section('content')
<div class="container">
    <h1>Agregar Color</h1>
    <form action="{{ route('colors.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="code">Código Hexadecimal</label>
                    <input type="color" name="code" id="code" class="form-control" required>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
    </form>
</div>
@endsection
