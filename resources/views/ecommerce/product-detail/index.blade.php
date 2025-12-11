@extends('ecommerce.base')

@php
    $metaDescription = $productDetail->description ? 
        Str::limit(strip_tags($productDetail->description), 155) : 
        'Descubre ' . $productDetail->name . ' en Iravic. Moda infantil de calidad con estilo y comodidad.';
    
    $keywords = ['ropa para niños', 'moda infantil', $productDetail->name];
    if($productDetail->category) {
        $keywords[] = $productDetail->category;
    }
    if($productDetail->brand) {
        $keywords[] = $productDetail->brand;
    }
    $metaKeywords = implode(', ', array_unique(array_map('strtolower', $keywords)));
@endphp

@section('title', $productDetail->name)
@section('meta-description', $metaDescription)
@section('meta-keywords', $metaKeywords)

{{-- Open Graph and Twitter Meta Tags --}}

@section('meta-tags')

{{-- Open Graph Meta Tags --}}  
<meta property="og:type" content="product">
<meta property="og:title" content="{{ $productDetail->name }} | Iravic">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:image" content="{{ !empty($productDetail->images) && isset($productDetail->images[0]) ? $productDetail->images[0] : asset('assets/cartzilla/images/og-image.jpg') }}">    
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:site_name" content="Iravic">
<meta property="og:locale" content="es_ES">

{{-- Product specific Open Graph tags --}}
@if($productDetail->price)
<meta property="product:price:amount" content="{{ $productDetail->price }}">
<meta property="product:price:currency" content="USD">
@endif
@if($productDetail->brand)
<meta property="product:brand" content="{{ $productDetail->brand }}">
@endif

{{-- Canonical handled globally in ecommerce.base layout --}}

{{-- Structured Data for Product --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Product",
  "name": "{{ $productDetail->name }}",
  "description": "{{ $metaDescription }}",
  "image": [
    @if($productDetail->images && count($productDetail->images) > 0)
      @foreach($productDetail->images as $index => $image)
        "{{ $image }}"{{ $index < count($productDetail->images) - 1 ? ',' : '' }}
      @endforeach
    @else
      "{{ asset('assets/cartzilla/images/og-image.jpg') }}"
    @endif
  ],
  "url": "{{ url()->current() }}",
  @if($productDetail->brand)
  "brand": {
    "@type": "Brand",
    "name": "{{ $productDetail->brand }}"
  },
  @endif
  @if($productDetail->price)
  "offers": {
    "@type": "Offer",
    "price": "{{ $productDetail->price }}",
    "priceCurrency": "USD",
    "availability": "https://schema.org/InStock",
    "url": "{{ url()->current() }}"
  },
  @endif
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.5",
    "reviewCount": "1"
  }
}
</script>

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
