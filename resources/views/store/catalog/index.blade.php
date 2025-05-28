 @extends('store.base')

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
