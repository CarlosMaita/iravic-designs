 @extends('ecommerce.base')

@php
    $titleSuffix = '';
    $descriptionSuffix = '';
    $keywords = ['catalogo', 'ecommerce', 'tienda online', 'ropa para niños', 'moda infantil'];
    
    if ($category) {
        $categoryObj = $categories->firstWhere('id', $category);
        if ($categoryObj) {
            $titleSuffix = ' - ' . $categoryObj->name;
            $descriptionSuffix = ' de ' . $categoryObj->name;
            $keywords[] = strtolower($categoryObj->name);
        }
    }
    
    if ($search) {
        $titleSuffix = ' - Búsqueda: ' . $search;
        $descriptionSuffix = ' para "' . $search . '"';
        $keywords[] = strtolower($search);
    }
    
    $pageTitle = 'Catalogo de la tienda' . $titleSuffix;
    $metaDescription = 'Descubre nuestro catálogo de productos de moda infantil' . $descriptionSuffix . '. Ropa cómoda y estilosa para niños y niñas.';
    $metaKeywords = implode(', ', array_unique($keywords));
@endphp

@section('title', $pageTitle)
@section('meta-description', $metaDescription)
@section('meta-keywords', $metaKeywords)

@section('meta-tags')
{{-- Open Graph Meta Tags --}}
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $pageTitle }} | Iravic">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:image" content="{{ asset('img/img-catalog.jpg') }}">
<meta property="og:url" content="{{ url()->current() }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}">
<meta property="og:site_name" content="Iravic">
<meta property="og:locale" content="es_ES">

{{-- Structured Data for Catalog --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "CollectionPage",
  "name": "{{ $pageTitle }}",
  "description": "{{ $metaDescription }}",
  "url": "{{ url()->current() }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}",
  "isPartOf": {
    "@type": "WebSite",
    "name": "Iravic",
    "url": "{{ url('/') }}"
  }
}
</script>

@endsection

 @section('breadcrumb')
  <!-- Breadcrumb -->
    <nav class="container" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Catalogo de la tienda</li>
        </ol>
    </nav>
  <!-- End Breadcrumb -->
 @endsection

 @section('content')
  {{-- catalog of ecommerce  --}}
  <catalog-ecommerce-component 
    :categories="{{ json_encode($categories) }}" 
    :brands="{{ json_encode($brands) }}"
    :genders="{{ json_encode($genders) }}"
    :colors="{{ json_encode($colors) }}"
    :search="{{ json_encode($search) }}"
    :category="{{ json_encode($category) }}"
    :brand="{{ json_encode($brand ?? null) }}"
    :gender="{{ json_encode($gender ?? null) }}"
    :color="{{ json_encode($color ?? null) }}"
    :min-price="{{ json_encode($minPrice ?? null) }}"
    :max-price="{{ json_encode($maxPrice ?? null) }}"
    ></catalog-ecommerce-component>
@endsection


@section('bottom-filter-buttom')
  <!-- Filter offcanvas toggle that is visible on screens < 992px wide (lg breakpoint) -->
    <button type="button" class="fixed-bottom z-sticky w-100 btn btn-lg btn-dark border-0 border-top border-light border-opacity-10 rounded-0 pb-4 d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#filterSidebar" aria-controls="filterSidebar" data-bs-theme="light">
      <i class="ci-filter fs-base me-2"></i>
        Filtros
    </button>
@endsection
