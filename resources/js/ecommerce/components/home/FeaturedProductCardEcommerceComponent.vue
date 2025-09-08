<template>
  <div class="col-6 col-md-4 mb-2 mb-sm-3 mb-md-0">
    <div v-if="!imageLoaded">
      <product-card-skeleton-ecommerce-component />
    </div>
    <div v-show="imageLoaded" class="animate-underline hover-effect-opacity">
      <div class="position-relative mb-3">
        <button type="button" class="btn btn-icon btn-secondary animate-pulse fs-base bg-transparent border-0 position-absolute top-0 end-0 z-2 mt-1 mt-sm-2 me-1 me-sm-2" aria-label="Add to Wishlist">
          <i class="ci-heart animate-target"></i>
        </button>
        <a class="d-flex bg-white border border-black rounded p-3" :href="productUrl">
          <div class="ratio" style="--cz-aspect-ratio: calc(308 / 274 * 100%)">
            <img 
              class="object-fit-contain product-image-zoom" 
              :src="productImage" 
              :alt="product.name" 
              @load="onImageLoad" 
              @error="onImageLoad" 
              style="display:block;" 
            />
          </div>
        </a>
      </div>
      <div class="nav mb-2">
        <a class="nav-link animate-target min-w-0 text-dark-emphasis p-0" :href="productUrl">
          <span class="text-truncate">{{ product.name }}</span>
        </a>
      </div>
      <div class="h6 mb-2">{{ formattedPrice }}</div>
    </div>
  </div>
</template>

<script>
import ProductCardSkeletonEcommerceComponent from '../catalog/ProductCardSkeletonEcommerceComponent.vue';

export default {
  name: 'FeaturedProductCardEcommerceComponent',
  components: {
    ProductCardSkeletonEcommerceComponent
  },
  props: {
    product: {
      type: Object,
      required: true,
    },
    productDetailRoute: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      imageLoaded: false
    };
  },
  computed: {
    productUrl() {
      return this.productDetailRoute.replace(':slug', this.product.slug);
    },
    productImage() {
      if (this.product.images && this.product.images.length > 0) {
        return this.product.images[0].full_url_img;
      }
      return '';
    },
    formattedPrice() {
      if (this.product.price) {
        return `$${Number(this.product.price).toFixed(2)}`;
      }
      if (this.product.regular_price_str) {
        return this.product.regular_price_str;
      }
      return '';
    }
  },
  methods: {
    onImageLoad() {
      this.imageLoaded = true;
    }
  }
};
</script>

<style scoped>
/* Zoom effect on image hover */
.product-image-zoom {
  transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.product-image-zoom:hover {
  transform: scale(1.1);
  z-index: 2;
}
</style>