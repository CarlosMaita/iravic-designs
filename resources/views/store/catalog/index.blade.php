 @extends('store.base')

@section('title', 'Catalogo')
@section('meta-description', 'Catalogo de productos de la tienda')
@section('meta-keywords', 'catalogo, ecommerce, tienda online, ropa para niños')

@section('meta-tags')
{{-- Open Graph Meta Tags --}}
<meta property="og:type" content="website">
<meta property="og:title" content="Catalogo de Productos">
<meta property="og:description" content="Explora nuestro catalogo de productos de moda infantil">
<meta property="og:image" content="{{ asset('assets/cartzilla/images/og-image.jpg') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:site_name" content="Iravic">
<meta property="og:locale" content="es_ES">

</script>

@endsection

 @section('breadcrumb')
  <!-- Breadcrumb -->
    <nav class="container" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Catalogo</li>
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
    ></catalog-ecommerce-component>
@endsection


@section('bottom-filter-buttom')
  <!-- Filter offcanvas toggle that is visible on screens < 992px wide (lg breakpoint) -->
    <button type="button" class="fixed-bottom z-sticky w-100 btn btn-lg btn-dark border-0 border-top border-light border-opacity-10 rounded-0 pb-4 d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#filterSidebar" aria-controls="filterSidebar" data-bs-theme="light">
      <i class="ci-filter fs-base me-2"></i>
        Filtros
    </button>
@endsection
