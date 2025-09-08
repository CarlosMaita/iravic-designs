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
<!-- Special Offers Section with Cartzilla Design -->
<section class="container pt-4 pb-5 mb-2 mb-sm-3 mb-lg-4 mb-xl-5">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-xl-6">
      <div class="text-center pb-4 mb-2 mb-md-3">
        <h2 class="h1 mb-0">Ofertas Especiales</h2>
        <p class="fs-lg text-body-secondary mb-0">¡No te pierdas estas increíbles promociones por tiempo limitado!</p>
      </div>
    </div>
  </div>

  <!-- Special Offers Carousel -->
  <div class="position-relative">
    <div class="swiper special-offers-swiper" data-swiper='{
      "slidesPerView": 1,
      "spaceBetween": 24,
      "loop": true,
      "pagination": {
        "el": ".special-offers-pagination",
        "clickable": true
      },
      "navigation": {
        "nextEl": ".special-offers-next",
        "prevEl": ".special-offers-prev"
      },
      "breakpoints": {
        "576": {
          "slidesPerView": 1
        },
        "768": {
          "slidesPerView": 2
        },
        "992": {
          "slidesPerView": 2
        },
        "1200": {
          "slidesPerView": 3
        }
      }
    }'>
      <div class="swiper-wrapper">
        @foreach($offers as $offer)
          <div class="swiper-slide h-auto">
            <div class="position-relative d-flex flex-column h-100 bg-warning-subtle border border-warning rounded-5 overflow-hidden p-4">
              <!-- Discount Badge -->
              @if($offer->discount_percentage)
                <div class="position-absolute top-0 end-0 m-3 z-2">
                  <span class="badge bg-danger fs-6 px-3 py-2 rounded-pill">
                    -{{ number_format($offer->discount_percentage, 0) }}%
                  </span>
                </div>
              @endif

              <!-- Time Remaining Badge -->
              @if($offer->is_current && isset($offer->days_remaining))
                <div class="position-absolute top-0 start-0 m-3 z-2">
                  <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill">
                    @if($offer->days_remaining > 1)
                      {{ $offer->days_remaining }} días
                    @elseif($offer->days_remaining == 1)
                      ¡Último día!
                    @else
                      ¡Últimas horas!
                    @endif
                  </span>
                </div>
              @endif

              <div class="d-flex flex-column flex-grow-1">
                <!-- Offer Content -->
                <div class="flex-grow-1 mb-4">
                  <h3 class="h4 mb-3">{{ $offer->title }}</h3>
                  @if($offer->description)
                    <p class="text-body-secondary mb-3">{{ Str::limit($offer->description, 120) }}</p>
                  @endif
                  
                  <!-- Product Information -->
                  @if(isset($offer->product))
                    <div class="d-flex align-items-center mb-3">
                      @if($offer->product->images && $offer->product->images->count() > 0)
                        <div class="ratio rounded-circle overflow-hidden me-3" style="width: 48px; height: 48px;">
                          <img src="{{ $offer->product->images->first()->full_url_img }}" 
                               alt="{{ $offer->product->name }}" 
                               class="object-fit-cover">
                        </div>
                      @endif
                      <div>
                        <h6 class="mb-1">{{ $offer->product->name }}</h6>
                        <div class="d-flex align-items-center">
                          @if($offer->discount_percentage && isset($offer->product->price))
                            <span class="text-decoration-line-through me-2 fs-sm text-body-secondary">
                              ${{ number_format($offer->product->price, 2) }}
                            </span>
                            <span class="text-success fw-semibold">
                              ${{ number_format($offer->product->price * (1 - $offer->discount_percentage / 100), 2) }}
                            </span>
                          @elseif(isset($offer->product->price))
                            <span class="text-success fw-semibold">
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
                      <small class="text-body-secondary d-block mb-1">La oferta termina el:</small>
                      <strong class="text-danger">{{ $offer->end_date->format('d/m/Y H:i') }}</strong>
                    </div>
                  @endif
                </div>

                <!-- Action Button -->
                <div class="nav">
                  @if(isset($offer->product))
                    <a class="nav-link animate-underline stretched-link text-body-emphasis text-nowrap px-0" 
                       href="{{ route('ecommerce.product.detail', $offer->product->slug) }}">
                      <span class="animate-target">Ver Producto</span>
                      <i class="ci-chevron-right fs-base ms-1"></i>
                    </a>
                  @else
                    <a class="nav-link animate-underline stretched-link text-body-emphasis text-nowrap px-0" 
                       href="{{ route('ecommerce.catalog') }}">
                      <span class="animate-target">Ver Catálogo</span>
                      <i class="ci-chevron-right fs-base ms-1"></i>
                    </a>
                  @endif
                </div>
              </div>

              <!-- Offer Image as Background Element -->
              @if($offer->image_url)
                <div class="ratio position-absolute bottom-0 end-0 rtl-flip opacity-25" style="max-width: 180px; --cz-aspect-ratio: calc(200 / 180 * 100%); margin-bottom: -20px; margin-right: -20px;">
                  <img src="{{ $offer->image_url }}" alt="{{ $offer->title }}" class="object-fit-cover">
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <!-- Navigation buttons -->
    <button type="button" class="btn btn-icon btn-outline-secondary special-offers-prev position-absolute top-50 start-0 translate-middle-y z-5 ms-n5 d-none d-xl-inline-flex" aria-label="Previous">
      <i class="ci-chevron-left"></i>
    </button>
    <button type="button" class="btn btn-icon btn-outline-secondary special-offers-next position-absolute top-50 end-0 translate-middle-y z-5 me-n5 d-none d-xl-inline-flex" aria-label="Next">
      <i class="ci-chevron-right"></i>
    </button>

    <!-- Pagination (bullets) -->
    <div class="swiper-pagination special-offers-pagination d-xl-none pt-4"></div>
  </div>

  <!-- View All Offers Button -->
  @if($specialOffers->count() > 3)
    <div class="text-center pt-4 mt-2 mt-md-3">
      <a href="{{ route('ecommerce.catalog') }}" class="btn btn-outline-primary">
        <i class="ci-eye fs-base me-2"></i>
        Ver Todas las Ofertas
      </a>
    </div>
  @endif
</section>
@endif