@php
// Create mock data for testing if no products exist
$mockProducts = collect();
if($featuredProducts->count() == 0) {
  $mockProducts = collect([
    (object) [
      'id' => 1,
      'name' => 'Vestido Elegante Rosa',
      'slug' => 'vestido-elegante-rosa',
      'price' => 25.99,
      'images' => collect([(object) ['full_url_img' => 'data:image/svg+xml;base64,' . base64_encode('
        <svg xmlns="http://www.w3.org/2000/svg" width="300" height="300" viewBox="0 0 300 300">
          <rect width="300" height="300" fill="#ff69b4"/>
          <rect x="75" y="100" width="150" height="120" fill="#ffffff" opacity="0.2"/>
          <circle cx="125" cy="120" r="15" fill="#ffffff" opacity="0.3"/>
          <circle cx="175" cy="120" r="15" fill="#ffffff" opacity="0.3"/>
          <text x="150" y="250" text-anchor="middle" fill="#ffffff" font-family="Arial" font-size="16" font-weight="bold">Vestido</text>
        </svg>
      ')]]),
      'category' => (object) ['name' => 'Vestidos']
    ],
    (object) [
      'id' => 2,
      'name' => 'Camisa Azul Niño',
      'slug' => 'camisa-azul-nino',
      'price' => 18.50,
      'images' => collect([(object) ['full_url_img' => 'data:image/svg+xml;base64,' . base64_encode('
        <svg xmlns="http://www.w3.org/2000/svg" width="300" height="300" viewBox="0 0 300 300">
          <rect width="300" height="300" fill="#4169e1"/>
          <rect x="100" y="80" width="100" height="140" fill="#ffffff" opacity="0.2"/>
          <rect x="110" y="130" width="80" height="50" fill="#ffffff" opacity="0.1"/>
          <text x="150" y="250" text-anchor="middle" fill="#ffffff" font-family="Arial" font-size="16" font-weight="bold">Camisa</text>
        </svg>
      ')]]),
      'category' => (object) ['name' => 'Camisas']
    ],
    (object) [
      'id' => 3,
      'name' => 'Falda Verde Niña',
      'slug' => 'falda-verde-nina',
      'price' => 22.00,
      'images' => collect([(object) ['full_url_img' => 'data:image/svg+xml;base64,' . base64_encode('
        <svg xmlns="http://www.w3.org/2000/svg" width="300" height="300" viewBox="0 0 300 300">
          <rect width="300" height="300" fill="#32cd32"/>
          <path d="M120 130 L180 130 L200 220 L100 220 Z" fill="#ffffff" opacity="0.2"/>
          <text x="150" y="250" text-anchor="middle" fill="#ffffff" font-family="Arial" font-size="16" font-weight="bold">Falda</text>
        </svg>
      ')]]),
      'category' => (object) ['name' => 'Faldas']
    ],
    (object) [
      'id' => 4,
      'name' => 'Pantalón Casual Gris',
      'slug' => 'pantalon-casual-gris',
      'price' => 28.75,
      'images' => collect([(object) ['full_url_img' => 'data:image/svg+xml;base64,' . base64_encode('
        <svg xmlns="http://www.w3.org/2000/svg" width="300" height="300" viewBox="0 0 300 300">
          <rect width="300" height="300" fill="#808080"/>
          <rect x="120" y="100" width="25" height="120" fill="#ffffff" opacity="0.2"/>
          <rect x="155" y="100" width="25" height="120" fill="#ffffff" opacity="0.2"/>
          <text x="150" y="250" text-anchor="middle" fill="#ffffff" font-family="Arial" font-size="14" font-weight="bold">Pantalón</text>
        </svg>
      ')]]),
      'category' => (object) ['name' => 'Pantalones']
    ],
    (object) [
      'id' => 5,
      'name' => 'Blusa Amarilla',
      'slug' => 'blusa-amarilla',
      'price' => 16.99,
      'images' => collect([(object) ['full_url_img' => 'data:image/svg+xml;base64,' . base64_encode('
        <svg xmlns="http://www.w3.org/2000/svg" width="300" height="300" viewBox="0 0 300 300">
          <rect width="300" height="300" fill="#ffd700"/>
          <rect x="100" y="90" width="100" height="80" fill="#ffffff" opacity="0.2"/>
          <circle cx="150" cy="110" r="20" fill="#ffffff" opacity="0.1"/>
          <text x="150" y="250" text-anchor="middle" fill="#333333" font-family="Arial" font-size="16" font-weight="bold">Blusa</text>
        </svg>
      ')]]),
      'category' => (object) ['name' => 'Blusas']
    ],
    (object) [
      'id' => 6,
      'name' => 'Shorts Deportivo',
      'slug' => 'shorts-deportivo',
      'price' => 14.25,
      'images' => collect([(object) ['full_url_img' => 'data:image/svg+xml;base64,' . base64_encode('
        <svg xmlns="http://www.w3.org/2000/svg" width="300" height="300" viewBox="0 0 300 300">
          <rect width="300" height="300" fill="#ff4500"/>
          <rect x="110" y="120" width="35" height="60" fill="#ffffff" opacity="0.2"/>
          <rect x="155" y="120" width="35" height="60" fill="#ffffff" opacity="0.2"/>
          <text x="150" y="250" text-anchor="middle" fill="#ffffff" font-family="Arial" font-size="16" font-weight="bold">Shorts</text>
        </svg>
      ')]]),
      'category' => (object) ['name' => 'Deportivos']
    ]
  ]);
}
$displayProducts = $featuredProducts->count() > 0 ? $featuredProducts : $mockProducts;
@endphp

@if($displayProducts->count() > 0)
<!-- Featured Products Carousel -->
<section class="py-5 bg-body-tertiary">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-xl-6">
        <div class="text-center pb-4 mb-2 mb-md-3">
          <h2 class="h1 mb-0">Productos Destacados</h2>
          <p class="fs-lg text-body-secondary mb-0">Descubre nuestra selección destacada de productos</p>
        </div>
      </div>
    </div>
    
    <div class="position-relative">
      <!-- Swiper slider -->
      <div class="swiper featured-products-swiper" data-swiper='{
        "slidesPerView": 1,
        "spaceBetween": 24,
        "loop": true,
        "pagination": {
          "el": ".featured-products-pagination",
          "clickable": true
        },
        "navigation": {
          "nextEl": ".featured-products-next",
          "prevEl": ".featured-products-prev"
        },
        "breakpoints": {
          "576": {
            "slidesPerView": 2
          },
          "768": {
            "slidesPerView": 3
          },
          "992": {
            "slidesPerView": 4
          },
          "1200": {
            "slidesPerView": 6
          }
        }
      }'>
        <div class="swiper-wrapper">
          @foreach($displayProducts as $product)
            <div class="swiper-slide">
              <!-- Product card with catalog design -->
              <div class="mb-2 mb-sm-3 mb-md-0">
                <div class="animate-underline hover-effect-opacity">
                  <div class="position-relative mb-3">
                    <button type="button" class="btn btn-icon btn-secondary animate-pulse fs-base bg-transparent border-0 position-absolute top-0 end-0 z-2 mt-1 mt-sm-2 me-1 me-sm-2" aria-label="Add to Wishlist">
                      <i class="ci-heart animate-target"></i>
                    </button>
                    <a class="d-flex bg-white border border-black rounded p-3" href="{{ route('ecommerce.product.detail', $product->slug) }}">
                      <div class="ratio" style="--cz-aspect-ratio: calc(308 / 274 * 100%)">
                        @if($product->images->count() > 0)
                          <img class="object-fit-contain product-image-zoom" 
                               src="{{ $product->images->first()->full_url_img }}" 
                               alt="{{ $product->name }}" 
                               style="display:block;" />
                        @else
                          <div class="d-flex align-items-center justify-content-center h-100">
                            <i class="fas fa-image text-muted" style="font-size: 2rem;"></i>
                          </div>
                        @endif
                      </div>
                    </a>
                  </div>
                  <div class="nav mb-2">
                    <a class="nav-link animate-target min-w-0 text-dark-emphasis p-0" href="{{ route('ecommerce.product.detail', $product->slug) }}">
                      <span class="h6 mb-0 fw-bold">
                        @if($product->price)
                          ${{ number_format($product->price, 2) }}
                        @endif
                      </span>
                    </a>
                  </div>
                  <div class="text-muted small">
                    <span class="text-truncate">{{ $product->name }}</span>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
      
      <!-- Navigation buttons -->
      <button type="button" class="btn btn-icon btn-outline-primary featured-products-prev position-absolute top-50 start-0 translate-middle-y z-5 ms-n5 d-none d-xl-inline-flex">
        <i class="fas fa-chevron-left"></i>
      </button>
      <button type="button" class="btn btn-icon btn-outline-primary featured-products-next position-absolute top-50 end-0 translate-middle-y z-5 me-n5 d-none d-xl-inline-flex">
        <i class="fas fa-chevron-right"></i>
      </button>
      
      <!-- Pagination (bullets) -->
      <div class="swiper-pagination featured-products-pagination d-xl-none pt-4"></div>
    </div>
  </div>
</section>

<style>
/* Zoom effect on image hover */
.product-image-zoom {
  transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.product-image-zoom:hover {
  transform: scale(1.1);
  z-index: 2;
}

/* Navigation button styles */
.btn-icon {
  width: 3rem;
  height: 3rem;
  padding: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: #fff;
  border: 2px solid #e9ecef;
  color: #495057;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.btn-icon:hover {
  background: #007bff;
  border-color: #007bff;
  color: #fff;
  transform: scale(1.1);
  box-shadow: 0 6px 20px rgba(0, 123, 255, 0.3);
}

.btn-icon:focus {
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Swiper pagination customization */
.featured-products-pagination {
  text-align: center;
  margin-top: 2rem;
}

.featured-products-pagination .swiper-pagination-bullet {
  width: 12px;
  height: 12px;
  background: #dee2e6;
  opacity: 1;
  margin: 0 6px;
  transition: all 0.3s ease;
}

.featured-products-pagination .swiper-pagination-bullet-active {
  background: #007bff;
  transform: scale(1.2);
}

/* Responsive adjustments */
@media (max-width: 767.98px) {
  .btn-icon {
    width: 2.5rem;
    height: 2.5rem;
  }
}

/* Ensure z-index for navigation buttons */
.featured-products-prev,
.featured-products-next {
  z-index: 10;
}
</style>
@endif