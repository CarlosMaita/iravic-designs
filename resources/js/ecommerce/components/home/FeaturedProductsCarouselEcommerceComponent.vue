<template>
  <section v-if="featuredProducts && featuredProducts.length > 0" class="py-5 bg-body-tertiary">
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
        <div 
          ref="featuredSwiper" 
          class="swiper featured-products-swiper"
        >
          <div class="swiper-wrapper">
            <div 
              v-for="product in featuredProducts" 
              :key="product.id"
              class="swiper-slide"
            >
              <div class="product-card">
                <a :href="getProductUrl(product.slug)" class="product-link">
                  <div class="product-image-container">
                    <img 
                      v-if="product.images && product.images.length > 0" 
                      :src="product.images[0].full_url_img" 
                      :alt="product.name" 
                      class="product-image"
                    >
                    <div v-else class="product-placeholder">
                      <i class="fas fa-image text-muted"></i>
                      <p class="text-muted small mb-0">Sin imagen</p>
                    </div>
                  </div>
                  <div class="product-info pt-3">
                    <h6 class="mb-1 fw-bold">{{ product.name }}</h6>
                    <p v-if="product.regular_price_str" class="mb-0 text-primary fw-bold">{{ product.regular_price_str }}</p>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Navigation buttons -->
        <button 
          ref="featuredPrev"
          type="button" 
          class="btn btn-icon btn-outline-primary featured-products-prev position-absolute top-50 start-0 translate-middle-y z-5 ms-n5 d-none d-xl-inline-flex"
        >
          <i class="fas fa-chevron-left"></i>
        </button>
        <button 
          ref="featuredNext"
          type="button" 
          class="btn btn-icon btn-outline-primary featured-products-next position-absolute top-50 end-0 translate-middle-y z-5 me-n5 d-none d-xl-inline-flex"
        >
          <i class="fas fa-chevron-right"></i>
        </button>
        
        <!-- Pagination (bullets) -->
        <div ref="featuredPagination" class="swiper-pagination featured-products-pagination d-xl-none pt-4"></div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'FeaturedProductsCarouselEcommerceComponent',
  props: {
    featuredProducts: {
      type: Array,
      default: () => []
    },
    productDetailRoute: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      swiper: null
    }
  },
  methods: {
    getProductUrl(slug) {
      return this.productDetailRoute.replace(':slug', slug);
    },
    
    initializeSwiper() {
      // Ensure Swiper is available
      if (typeof window.Swiper === 'undefined') {
        console.warn('Swiper is not available');
        return;
      }

      // Initialize Swiper
      this.swiper = new window.Swiper(this.$refs.featuredSwiper, {
        slidesPerView: 2,
        spaceBetween: 24,
        loop: this.featuredProducts.length > 2,
        pagination: {
          el: this.$refs.featuredPagination,
          clickable: true
        },
        navigation: {
          nextEl: this.$refs.featuredNext,
          prevEl: this.$refs.featuredPrev
        },
        breakpoints: {
          576: {
            slidesPerView: 3
          },
          768: {
            slidesPerView: 4
          },
          992: {
            slidesPerView: 5
          },
          1200: {
            slidesPerView: 6
          }
        }
      });
    },
    
    destroySwiper() {
      if (this.swiper) {
        this.swiper.destroy(true, true);
        this.swiper = null;
      }
    }
  },
  
  mounted() {
    this.$nextTick(() => {
      if (this.featuredProducts && this.featuredProducts.length > 0) {
        this.initializeSwiper();
      }
    });
  },
  
  beforeDestroy() {
    this.destroySwiper();
  },
  
  watch: {
    featuredProducts: {
      handler(newProducts) {
        this.destroySwiper();
        if (newProducts && newProducts.length > 0) {
          this.$nextTick(() => {
            this.initializeSwiper();
          });
        }
      },
      deep: true
    }
  }
}
</script>

<style scoped>
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

/* Ensure z-index for navigation buttons */
.featured-products-prev,
.featured-products-next {
  z-index: 10;
}

/* Swiper specific styles */
.swiper-pagination-bullet {
  background: #007bff;
}

.swiper-pagination-bullet-active {
  background: #0056b3;
}
</style>