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