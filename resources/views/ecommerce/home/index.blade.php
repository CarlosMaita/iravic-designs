@extends('ecommerce.base')

@section('title', 'Ropa Fashion para Niños y Niñas')
@section('meta-description', 'El mejor catalogo de ropa para niños y niñas, descubre las últimas tendencias en moda infantil. Encuentra ropa cómoda y estilosa para tus pequeños con envíos rápidos y seguros.')
@section('meta-keywords', 'catalogo, ecommerce, tienda online, ropa para niños')

@section('meta-tags')
{{-- Open Graph Meta Tags --}}
<meta property="og:type" content="website">
<meta property="og:title" content="Ropa Fashion para Niños y Niñas | Iravic">
<meta property="og:description" content="El mejor catálogo de ropa para niños y niñas, descubre las últimas tendencias en moda infantil. Encuentra ropa cómoda y estilosa para tus pequeños con envíos rápidos y seguros.">
<meta property="og:image" content="{{ asset('img/img-catalog.jpg') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:site_name" content="Iravic">
<meta property="og:locale" content="es_ES">

{{-- Structured Data for Homepage --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "Iravic",
  "description": "El mejor catálogo de ropa para niños y niñas, descubre las últimas tendencias en moda infantil.",
  "url": "{{ url('/') }}",
  "potentialAction": {
    "@type": "SearchAction",
    "target": {
      "@type": "EntryPoint",
      "urlTemplate": "{{ route('ecommerce.catalog') }}?search={search_term_string}"
    },
    "query-input": "required name=search_term_string"
  },
  "sameAs": [
    "https://www.facebook.com/iravicdesigns",
    "https://www.instagram.com/iravicdesigns"
  ]
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Iravic",
  "url": "{{ url('/') }}",
  "logo": "{{ asset('img/logo.png') }}",
  "description": "Tienda especializada en moda infantil con las últimas tendencias para niños y niñas.",
  "contactPoint": {
    "@type": "ContactPoint",
    "contactType": "Customer Service",
    "areaServed": "Colombia"
  }
}
</script>
@endsection

@section('content')

  {{--  Carousel --}}
  @include('ecommerce.home.partials.carousel', ['banners' => $banners])


  {{-- Productos Destacados (Diseño Figma) --}}
  @include('ecommerce.home.partials.featured-products-figma', ['featuredProducts' => $featuredProducts])

  <special-offers-carousel-ecommerce-component
    :offers='@json($specialOffers)'
    catalog-route='{{ route('ecommerce.catalog') }}'
  />

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
