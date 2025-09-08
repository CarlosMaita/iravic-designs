@extends('dashboard.base')

@section('content')
<div class="container-fluid">
    <div class="fade-in">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-edit"></i> Editar Oferta Especial: {{ $specialOffer->title }}
                    </div>
                    <div class="card-body">
                        @include('dashboard.shared.flash-message')
                        
                        <form action="{{ route('special-offers.update', $specialOffer) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('admin.special-offers._form')
                            
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('special-offers.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> Volver
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Actualizar Oferta
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection