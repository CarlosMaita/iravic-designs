<template>
  <section v-if="featuredProducts && featuredProducts.length > 0" class="py-5 bg-body-tertiary">
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
        <div 
          ref="featuredSwiper" 
          class="swiper featured-products-swiper"
          :data-swiper="swiperConfig"
        >
          <div class="swiper-wrapper">
            <div 
              v-for="product in featuredProducts" 
              :key="product.id"
              class="swiper-slide"
            >
              <featured-product-card-ecommerce-component
                :product="product"
                :product-detail-route="productDetailRoute"
              />
            </div>
          </div>
        </div>
        
        <!-- Navigation buttons -->
        <button 
          type="button" 
          class="btn btn-icon btn-outline-primary featured-products-prev position-absolute top-50 start-0 translate-middle-y z-5 ms-n5 d-none d-xl-inline-flex"
        >
          <i class="fas fa-chevron-left"></i>
        </button>
        <button 
          type="button" 
          class="btn btn-icon btn-outline-primary featured-products-next position-absolute top-50 end-0 translate-middle-y z-5 me-n5 d-none d-xl-inline-flex"
        >
          <i class="fas fa-chevron-right"></i>
        </button>
        
        <!-- Pagination (bullets) -->
        <div class="swiper-pagination featured-products-pagination d-xl-none pt-4"></div>
      </div>
    </div>
  </section>
</template>

<script>
import FeaturedProductCardEcommerceComponent from './FeaturedProductCardEcommerceComponent.vue';

export default {
  name: 'FeaturedProductsCarouselEcommerceComponent',
  components: {
    FeaturedProductCardEcommerceComponent
  },
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
  computed: {
    swiperConfig() {
      return JSON.stringify({
        slidesPerView: 2,
        spaceBetween: 24,
        loop: true,
        pagination: {
          el: '.featured-products-pagination',
          clickable: true
        },
        navigation: {
          nextEl: '.featured-products-next',
          prevEl: '.featured-products-prev'
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
    }
  },
  methods: {
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
          el: '.featured-products-pagination',
          clickable: true
        },
        navigation: {
          nextEl: '.featured-products-next',
          prevEl: '.featured-products-prev'
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