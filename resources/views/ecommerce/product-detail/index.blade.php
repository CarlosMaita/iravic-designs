@extends('ecommerce.base')

@section('title', $productDetail->name)
@section('meta-description', $productDetail->description)

{{-- Open Graph and Twitter Meta Tags --}}

@section('meta-tags')

{{-- Open Graph Meta Tags --}}  
<meta property="og:type" content="product">
<meta property="og:title" content="{{ $productDetail->name }}">
<meta property="og:description" content="{{ $productDetail->description }}">
<meta property="og:image" content="{{ $productDetail->images[0] ? $productDetail->images[0] : asset('assets/cartzilla/images/og-image.jpg') }}">    

<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:site_name" content="Iravic">
<meta property="og:locale" content="es_ES">

{{-- Canonical URL --}}
<link rel="canonical" href="{{ url()->current() }}">

@endsection

@section('breadcrumb')
<!-- Breadcrumb -->
<nav class="container" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/" >Inicio</a></li>
        <li class="breadcrumb-item"><a href="/catalogo">Catologo</a></li>
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
