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
        "slidesPerView": 2,
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
            "slidesPerView": 3
          },
          "768": {
            "slidesPerView": 4
          },
          "992": {
            "slidesPerView": 5
          },
          "1200": {
            "slidesPerView": 6
          }
        }
      }'>
        <div class="swiper-wrapper">
          @foreach($displayProducts as $product)
            <div class="swiper-slide">
              <div class="product-card">
                <a href="{{ route('ecommerce.product.detail', $product->slug) }}" class="product-link">
                  <div class="product-image-container">
                    @if($product->images->count() > 0)
                      <img src="{{ $product->images->first()->full_url_img }}" 
                           alt="{{ $product->name }}" 
                           class="product-image">
                    @else
                      <div class="product-placeholder">
                        <i class="fas fa-image text-muted"></i>
                        <p class="text-muted small mb-0">Sin imagen</p>
                      </div>
                    @endif
                    
                    <!-- Product overlay with category -->
                    <div class="product-overlay">
                      @if(isset($product->category))
                        <span class="product-category">{{ $product->category->name }}</span>
                      @endif
                    </div>
                  </div>
                  
                  <!-- Product info -->
                  <div class="product-info">
                    <h6 class="product-name">{{ $product->name }}</h6>
                    @if($product->price)
                      <div class="product-price">${{ number_format($product->price, 2) }}</div>
                    @endif
                  </div>
                </a>
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
.product-card {
  transition: all 0.3s ease;
  background: #fff;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  border: 1px solid rgba(0, 0, 0, 0.04);
  height: 100%;
  position: relative;
}

.product-link {
  display: block;
  text-decoration: none;
  color: inherit;
  height: 100%;
}

.product-image-container {
  position: relative;
  overflow: hidden;
  background: #f8f9fa;
  aspect-ratio: 4/5;
  display: flex;
  align-items: center;
  justify-content: center;
}

.product-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease;
}

.product-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100%;
  padding: 20px;
  color: #6c757d;
}

.product-placeholder i {
  font-size: 2rem;
  margin-bottom: 8px;
}

/* Product overlay */
.product-overlay {
  position: absolute;
  top: 12px;
  left: 12px;
  right: 12px;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  opacity: 0;
  transition: opacity 0.3s ease;
  pointer-events: none;
}

.product-category {
  background: rgba(255, 255, 255, 0.95);
  color: #495057;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 500;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.3);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Product info */
.product-info {
  padding: 16px;
  text-align: center;
}

.product-name {
  font-size: 0.9rem;
  font-weight: 600;
  color: #212529;
  margin-bottom: 8px;
  line-height: 1.3;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  min-height: 2.6rem;
}

.product-price {
  font-size: 1.1rem;
  font-weight: 700;
  color: #e74c3c;
  margin-bottom: 0;
}

/* Hover effects */
.product-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
  border-color: rgba(0, 0, 0, 0.1);
}

.product-card:hover .product-image {
  transform: scale(1.08);
}

.product-card:hover .product-overlay {
  opacity: 1;
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
  .product-card {
    border-radius: 12px;
  }
  
  .product-info {
    padding: 12px;
  }
  
  .product-name {
    font-size: 0.85rem;
  }
  
  .product-price {
    font-size: 1rem;
  }
  
  .btn-icon {
    width: 2.5rem;
    height: 2.5rem;
  }
}

@media (max-width: 575.98px) {
  .product-info {
    padding: 10px;
  }
  
  .product-name {
    font-size: 0.8rem;
    min-height: 2.4rem;
  }
  
  .product-price {
    font-size: 0.95rem;
  }
}

/* Enhanced focus styles for accessibility */
.product-link:focus {
  outline: 2px solid #007bff;
  outline-offset: 2px;
}

/* Loading state (if needed) */
.product-card.loading {
  opacity: 0.7;
  pointer-events: none;
}

.product-card.loading .product-image {
  filter: blur(2px);
}
</style>
@endif