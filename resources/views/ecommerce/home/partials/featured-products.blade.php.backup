@if($featuredProducts->count() > 0)
<!-- Featured Products Carousel -->
<section class="py-5 bg-body-tertiary">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-xl-6">
        <div class="text-center pb-4 mb-2 mb-md-3">
          <h2 class="h1 mb-0">Productos Destacados</h2>
          <p class="fs-lg text-body-secondary mb-0">Descubre nuestra selección especial de productos</p>
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
          @foreach($featuredProducts as $product)
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
  transition: transform 0.3s ease;
}

.product-link {
  display: block;
  text-decoration: none;
  color: inherit;
}

.product-image-container {
  position: relative;
  overflow: hidden;
  border-radius: 12px;
  background: #f8f9fa;
  aspect-ratio: 1;
  display: flex;
  align-items: center;
  justify-content: center;
}

.product-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
  border-radius: 12px;
}

.product-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100%;
  padding: 20px;
}

.product-placeholder i {
  font-size: 2rem;
  margin-bottom: 8px;
}

/* Hover effects */
.product-card:hover {
  transform: translateY(-4px);
}

.product-card:hover .product-image {
  transform: scale(1.05);
}

/* Navigation button styles */
.btn-icon {
  width: 3rem;
  height: 3rem;
  padding: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

/* Responsive adjustments */
@media (max-width: 767.98px) {
  .product-image-container {
    border-radius: 8px;
  }
  
  .product-image {
    border-radius: 8px;
  }
}
</style>
@endif