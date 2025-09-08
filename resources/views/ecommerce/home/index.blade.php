@extends('ecommerce.base')

@section('title', 'Ropa Fashion para Niños y Niñas')
@section('meta-description', 'El mejor catalogo de ropa para niños y niñas, descubre las últimas tendencias en moda infantil. Encuentra ropa cómoda y estilosa para tus pequeños con envíos rápidos y seguros.')
@section('meta-keywords', 'catalogo, ecommerce, tienda online, ropa para niños')

@section('meta-tags')
{{-- Open Graph Meta Tags --}}
<meta property="og:type" content="website">
<meta property="og:title" content="Ropa Fashion para Niños y Niñas">
<meta property="og:description" content="El mejor catalogo de ropa para niños y niñas, descubre las últimas tendencias en moda infantil. Encuentra ropa cómoda y estilosa para tus pequeños con envíos rápidos y seguros.">
<meta property="og:image" content="{{ asset('img/img-catalog.jpg') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:site_name" content="Iravic">
<meta property="og:locale" content="es_ES">

</script>

@endsection

@section('content')

  {{--  Carousel --}}
  @include('ecommerce.home.partials.carousel', ['banners' => $banners])

  {{--  Special Offers --}}
  @include('ecommerce.home.partials.special-offers', ['specialOffers' => $specialOffers])
  
  {{-- Main Categories  --}}
  {{-- @include('ecommerce.home.partials.main-categories') --}}

  {{--  Featured Products --}}
  @include('ecommerce.home.partials.featured-products', ['featuredProducts' => $featuredProducts])

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (window.Swiper) {
      new Swiper('.hero-swiper', {
        effect: "fade",
        loop: true,
        speed: 400,
        pagination: {
          el: ".swiper-pagination.hero",
          clickable: true
        },
        autoplay: {
          delay: 5500,
          disableOnInteraction: false
        }
      });
    }
  });
</script>
@endpush
