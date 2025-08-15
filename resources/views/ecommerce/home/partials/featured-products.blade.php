@if($featuredProducts->count() > 0)
<!-- Featured Products Carousel Vue Component -->
<featured-products-carousel-ecommerce-component 
  :featured-products="{{ json_encode($featuredProducts) }}"
  product-detail-route="{{ route('ecommerce.product.detail', ':slug') }}"
></featured-products-carousel-ecommerce-component>
@endif