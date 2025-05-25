 @extends('store.base')

@section('breadcrumb')
<!-- Breadcrumb -->
<nav class="container pt-2 pt-xxl-3 my-3 my-md-4" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a >Inicio</a></li>
        <li class="breadcrumb-item"><a href="/">Catologo</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{$productDetail->name}}</li>
    </ol>
</nav>
<!-- End Breadcrumb -->
@endsection

@section('content')
    {{-- Product detail Page --}}
    <product-detail-ecommerce-component :product="{{ json_encode($productDetail) }}"></product-detail-ecommerce-component>
    {{-- End Product detail Page --}}
@endsection
