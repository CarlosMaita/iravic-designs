<!-- Productos Destacados adaptado del diseño Figma -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="row justify-content-center">
      @foreach($featuredProducts as $product)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex align-items-stretch">
          <div class="card shadow-sm rounded-3 h-100 position-relative">
            <div class="position-relative" style="height:267px; overflow:hidden;">
              <img src="{{ $product->images[0] ?? asset('assets/cartzilla/images/og-image.jpg') }}" class="card-img-top object-fit-cover w-100 h-100" alt="{{ $product->name }}">
            </div>
            <div class="card-body d-flex flex-column justify-content-between">
              <h5 class="card-title fw-bold mb-2" style="font-size:16px;">{{ $product->name }}</h5>
              <div class="d-flex justify-content-between align-items-center mt-auto">
                <span class="text-dark" style="font-size:16px;">${{ number_format($product->price, 2) }}</span>
                <span class="badge bg-secondary">{{ $product->category->name ?? 'Producto' }}</span>
              </div>
            </div>
            <a href="{{ route('ecommerce.product.detail', $product->slug) }}" class="stretched-link" aria-label="Ver {{ $product->name }}"></a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
