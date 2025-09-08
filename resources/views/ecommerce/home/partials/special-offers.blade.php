@php
// Create mock data for testing if no special offers exist
$mockOffers = collect();
if($specialOffers->count() == 0) {
  $mockOffers = collect([
    (object) [
      'id' => 1,
      'title' => '¡Oferta Especial!',
      'description' => 'Hasta 50% de descuento en ropa para niños',
      'end_date' => \Carbon\Carbon::now()->addDays(7),
      'discount_percentage' => 50,
      'is_current' => true,
      'days_remaining' => 7,
      'image_url' => 'data:image/svg+xml;base64,' . base64_encode('
        <svg xmlns="http://www.w3.org/2000/svg" width="800" height="400" viewBox="0 0 800 400">
          <defs>
            <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" style="stop-color:#ff6b6b;stop-opacity:1" />
              <stop offset="100%" style="stop-color:#ffa500;stop-opacity:1" />
            </linearGradient>
          </defs>
          <rect width="800" height="400" fill="url(#grad1)"/>
          <text x="400" y="150" text-anchor="middle" fill="#ffffff" font-family="Arial" font-size="48" font-weight="bold">¡OFERTA ESPECIAL!</text>
          <text x="400" y="200" text-anchor="middle" fill="#ffffff" font-family="Arial" font-size="24">Hasta 50% de descuento</text>
          <text x="400" y="250" text-anchor="middle" fill="#ffffff" font-family="Arial" font-size="20">En toda la colección de niños</text>
          <text x="400" y="320" text-anchor="middle" fill="#ffffff" font-family="Arial" font-size="16">¡Solo por tiempo limitado!</text>
        </svg>
      '),
      'product' => (object) [
        'name' => 'Colección Infantil',
        'slug' => 'coleccion-infantil',
        'price' => 15.99,
        'images' => collect([(object) ['full_url_img' => 'data:image/svg+xml;base64,' . base64_encode('
          <svg xmlns="http://www.w3.org/2000/svg" width="300" height="300" viewBox="0 0 300 300">
            <rect width="300" height="300" fill="#4169e1"/>
            <rect x="100" y="80" width="100" height="140" fill="#ffffff" opacity="0.2"/>
            <text x="150" y="250" text-anchor="middle" fill="#ffffff" font-family="Arial" font-size="16" font-weight="bold">Niños</text>
          </svg>
        ')]]),
      ]
    ]
  ]);
}

$offers = $specialOffers->count() > 0 ? $specialOffers : $mockOffers;
@endphp

@if($offers->count() > 0)
<section class="py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
  <div class="container">
    <!-- Section Header -->
    <div class="text-center mb-5">
      <h2 class="h1 mb-3" style="color: #2c3e50; font-weight: 700;">🎯 Ofertas Especiales</h2>
      <p class="lead text-muted">¡No te pierdas estas increíbles promociones por tiempo limitado!</p>
    </div>

    <!-- Special Offers Grid -->
    <div class="row g-4">
      @foreach($offers as $offer)
        <div class="col-lg-6 col-xl-4">
          <div class="card border-0 shadow-lg h-100 overflow-hidden" style="border-radius: 20px;">
            <!-- Offer Image -->
            <div class="position-relative overflow-hidden">
              <img src="{{ $offer->image_url }}" alt="{{ $offer->title }}" class="card-img-top" style="height: 250px; object-fit: cover; transition: transform 0.3s ease;">
              
              <!-- Discount Badge -->
              @if($offer->discount_percentage)
                <div class="position-absolute top-0 end-0 m-3">
                  <span class="badge bg-danger fs-6 px-3 py-2" style="border-radius: 25px; font-weight: 600;">
                    -{{ number_format($offer->discount_percentage, 0) }}%
                  </span>
                </div>
              @endif

              <!-- Time Remaining Badge -->
              @if($offer->is_current && isset($offer->days_remaining))
                <div class="position-absolute bottom-0 start-0 m-3">
                  <span class="badge bg-warning text-dark fs-6 px-3 py-2" style="border-radius: 25px; font-weight: 600;">
                    @if($offer->days_remaining > 1)
                      {{ $offer->days_remaining }} días restantes
                    @elseif($offer->days_remaining == 1)
                      ¡Último día!
                    @else
                      ¡Últimas horas!
                    @endif
                  </span>
                </div>
              @endif
            </div>

            <div class="card-body p-4">
              <!-- Offer Title -->
              <h3 class="card-title h4 mb-3" style="color: #2c3e50; font-weight: 600;">
                {{ $offer->title }}
              </h3>

              <!-- Offer Description -->
              @if($offer->description)
                <p class="card-text text-muted mb-3">
                  {{ Str::limit($offer->description, 100) }}
                </p>
              @endif

              <!-- Product Information -->
              @if(isset($offer->product))
                <div class="d-flex align-items-center mb-3">
                  @if($offer->product->images && $offer->product->images->count() > 0)
                    <img src="{{ $offer->product->images->first()->full_url_img }}" alt="{{ $offer->product->name }}" 
                         class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                  @endif
                  <div>
                    <h6 class="mb-1 fw-semibold">{{ $offer->product->name }}</h6>
                    <div class="d-flex align-items-center">
                      @if($offer->discount_percentage && isset($offer->product->price))
                        <span class="text-muted text-decoration-line-through me-2">
                          ${{ number_format($offer->product->price, 2) }}
                        </span>
                        <span class="text-success fw-bold">
                          ${{ number_format($offer->product->price * (1 - $offer->discount_percentage / 100), 2) }}
                        </span>
                      @elseif(isset($offer->product->price))
                        <span class="text-success fw-bold">
                          ${{ number_format($offer->product->price, 2) }}
                        </span>
                      @endif
                    </div>
                  </div>
                </div>
              @endif

              <!-- Countdown Timer -->
              @if($offer->is_current && isset($offer->end_date))
                <div class="mb-3">
                  <small class="text-muted d-block mb-1">La oferta termina el:</small>
                  <strong class="text-danger">{{ $offer->end_date->format('d/m/Y H:i') }}</strong>
                </div>
              @endif
            </div>

            <!-- Card Footer with Action Button -->
            <div class="card-footer bg-transparent border-0 p-4 pt-0">
              @if(isset($offer->product))
                <a href="{{ route('ecommerce.product.detail', $offer->product->slug) }}" 
                   class="btn btn-primary w-100 py-3 fw-semibold" 
                   style="border-radius: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; transition: all 0.3s ease;">
                  <i class="fas fa-shopping-cart me-2"></i>
                  Ver Producto
                </a>
              @else
                <a href="{{ route('ecommerce.catalog') }}" 
                   class="btn btn-primary w-100 py-3 fw-semibold" 
                   style="border-radius: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; transition: all 0.3s ease;">
                  <i class="fas fa-shopping-cart me-2"></i>
                  Ver Catálogo
                </a>
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <!-- View All Offers Button -->
    @if($specialOffers->count() > 3)
      <div class="text-center mt-5">
        <a href="{{ route('ecommerce.catalog') }}" class="btn btn-outline-primary btn-lg px-5 py-3" style="border-radius: 25px; font-weight: 600;">
          <i class="fas fa-eye me-2"></i>
          Ver Todas las Ofertas
        </a>
      </div>
    @endif
  </div>
</section>

@push('styles')
<style>
.card:hover {
  transform: translateY(-5px);
  transition: transform 0.3s ease;
}

.card:hover .card-img-top {
  transform: scale(1.05);
}

.btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

@media (max-width: 768px) {
  .card-img-top {
    height: 200px;
  }
  
  .h1 {
    font-size: 2rem;
  }
}
</style>
@endpush
@endif