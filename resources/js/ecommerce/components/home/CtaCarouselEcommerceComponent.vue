<template>
  <section v-if="ctas && ctas.length > 0" class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
          <div class="text-center pb-4 mb-2 mb-md-3">
            <h2 class="h1 mb-0">Explora Nuestras Categorías</h2>
            <p class="fs-lg text-body-secondary mb-0">Encuentra lo que buscas rápidamente</p>
          </div>
        </div>
      </div>
      
      <div class="position-relative">
        <!-- Swiper slider -->
        <div 
          ref="ctaSwiper" 
          class="swiper cta-swiper"
          :id="ids.container"
        >
          <div class="swiper-wrapper">
            <div 
              v-for="cta in ctas" 
              :key="cta.id"
              class="swiper-slide"
            >
              <cta-card-ecommerce-component :cta="cta" />
            </div>
          </div>
        </div>
        
        <!-- Navigation buttons -->
        <button 
          type="button" 
          :id="ids.prev"
          class="btn btn-icon btn-outline-primary cta-prev position-absolute top-50 start-0 translate-middle-y z-5 ms-2 d-none d-xl-inline-flex"
        >
          <i class="fas fa-chevron-left"></i>
        </button>
        <button 
          type="button" 
          :id="ids.next"
          class="btn btn-icon btn-outline-primary cta-next position-absolute top-50 end-0 translate-middle-y z-5 me-2 d-none d-xl-inline-flex"
        >
          <i class="fas fa-chevron-right"></i>
        </button>
        
        <!-- Pagination (bullets) -->
        <div class="swiper-pagination cta-pagination d-xl-none pt-4" :id="ids.pagination"></div>
      </div>
    </div>
  </section>
</template>

<script>
import CtaCardEcommerceComponent from './CtaCardEcommerceComponent.vue';

export default {
  name: 'CtaCarouselEcommerceComponent',
  components: {
    CtaCardEcommerceComponent
  },
  props: {
    ctas: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      swiper: null,
      uniqueId: Math.random().toString(36).substring(2, 9)
    };
  },
  computed: {
    ids() {
      return {
        container: `cta-swiper-${this.uniqueId}`,
        prev: `cta-prev-${this.uniqueId}`,
        next: `cta-next-${this.uniqueId}`,
        pagination: `cta-pagination-${this.uniqueId}`
      };
    }
  },
  mounted() {
    this.$nextTick(() => {
      this.initSwiper();
    });
  },
  beforeDestroy() {
    if (this.swiper) {
      this.swiper.destroy(true, true);
    }
  },
  methods: {
    initSwiper() {
      if (!window.Swiper) {
        console.error('Swiper is not loaded');
        return;
      }

      try {
        this.swiper = new window.Swiper(`#${this.ids.container}`, {
          slidesPerView: 1,
          spaceBetween: 20,
          loop: this.ctas.length > 3,
          navigation: {
            nextEl: `#${this.ids.next}`,
            prevEl: `#${this.ids.prev}`,
          },
          pagination: {
            el: `#${this.ids.pagination}`,
            clickable: true,
            dynamicBullets: true
          },
          breakpoints: {
            576: {
              slidesPerView: 2,
              spaceBetween: 20
            },
            768: {
              slidesPerView: 3,
              spaceBetween: 24
            },
            992: {
              slidesPerView: 4,
              spaceBetween: 24
            },
            1200: {
              slidesPerView: 5,
              spaceBetween: 30
            }
          }
        });
      } catch (error) {
        console.error('Error initializing CTA Swiper:', error);
      }
    }
  }
};
</script>

<style scoped>
.cta-swiper {
  padding: 0 0 15px 0;
}

.btn-icon {
  width: 3rem;
  height: 3rem;
  padding: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
}
</style>
