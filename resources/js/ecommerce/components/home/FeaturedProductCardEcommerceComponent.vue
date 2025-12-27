<template>
  <div class="product-card animate-underline hover-effect-opacity bg-body rounded mb-4">
    <div v-if="!imageLoaded">
      <product-card-skeleton-ecommerce-component />
    </div>
    <div v-show="imageLoaded">
      <div class="position-relative">

        <!-- Product image with link -->
        <a class="d-block" :href="productUrl">
          <div class="ratio ratio-1x1 bg-body-tertiary rounded overflow-hidden">
            <img 
              class="object-fit-cover hover-effect-target product-image-zoom" 
              :src="productImage" 
              :alt="product.name" 
              @load="onImageLoad" 
              @error="onImageLoad" 
            />
          </div>
        </a>

        <!-- Badge overlay for special offers -->
        <div v-if="product.has_discount" class="position-absolute top-0 start-0 z-2 mt-2 ms-2">
          <span class="badge bg-danger">{{ discountLabel }}</span>
        </div>
      </div>

      <!-- Product info -->
      <div class="card-body px-2 pb-2 pt-3">
        <div class="nav mb-2">
          <a class="nav-link animate-target min-w-0 text-dark-emphasis p-0" :href="productUrl">
            <span class="text-truncate">{{ product.name }}</span>
          </a>
        </div>
        
        <!-- Price section -->
        <div class="mb-2">
          <div class="h6 mb-0 d-inline-flex align-items-center gap-2">
            <span class="text-dark-emphasis">{{ formattedPrice }}</span>
            <del v-if="product.original_price && product.original_price !== product.price" 
                 class="fs-sm fw-normal text-body-secondary">
              {{ formattedOriginalPrice }}
            </del>
          </div>
        </div>

        <!-- Rating stars (if available) -->
        <div v-if="product.rating" class="d-flex align-items-center gap-1 fs-xs">
          <div class="d-flex gap-1">
            <i v-for="star in 5" 
               :key="star"
               :class="[
                 'ci-star',
                 star <= Math.floor(product.rating) ? 'text-warning' : 'text-body-secondary'
               ]"
            ></i>
          </div>
          <span class="text-body-secondary">{{ product.rating }}</span>
          <span v-if="product.reviews_count" class="text-body-secondary">({{ product.reviews_count }})</span>
        </div>
      </div>
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
        // Find the primary image first
        const primaryImage = this.product.images.find(img => img.is_primary);
        if (primaryImage) {
          return primaryImage.full_url_img;
        }
        // Fallback to first image if no primary is set
        return this.product.images[0].full_url_img;
      }
      return '/assets/img/placeholder-product.svg'; // Cartzilla placeholder
    },
    formattedPrice() {
      if (this.product.price) {
        return `$${Number(this.product.price).toFixed(2)}`;
      }
      if (this.product.regular_price_str) {
        return this.product.regular_price_str;
      }
      return '';
    },
    formattedOriginalPrice() {
      if (this.product.original_price) {
        return `$${Number(this.product.original_price).toFixed(2)}`;
      }
      return '';
    },
    discountLabel() {
      if (this.product.discount_percentage) {
        return `-${this.product.discount_percentage}%`;
      }
      return 'Sale';
    }
  },
  methods: {
    onImageLoad() {
      this.imageLoaded = true;
    },
    
  }
};
</script>

<style scoped>
/* Product card following Cartzilla standards */
.product-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  max-width: 306px; /* Cartzilla standard width */
  margin: 0 auto;
}

.product-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  z-index: 2;
}

/* Image hover effects following Cartzilla */
.product-image-zoom {
  transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.product-card:hover .product-image-zoom {
  transform: scale(1.05);
}

/* Hover effects for action buttons */
.hover-effect-target {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.product-card:hover .hover-effect-target {
  opacity: 1 !important;
}

/* Button styling following Cartzilla */
.btn-icon {
  width: 2.5rem;
  height: 2.5rem;
  padding: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  transition: all 0.3s ease;
}

.btn-icon:hover {
  transform: scale(1.1);
}

/* Product card button styling */
.product-card-button {
  transition: all 0.3s ease;
  border-color: transparent;
  background: transparent;
}

.product-card:hover .product-card-button {
  background-color: var(--bs-primary);
  border-color: var(--bs-primary);
  color: white;
}

.product-card:hover .product-card-button:hover {
  background-color: var(--bs-primary);
  border-color: var(--bs-primary);
  color: white;
  transform: scale(1.1);
}

/* Animate scale effect */
.animate-scale {
  transition: transform 0.2s ease;
}

.animate-scale:hover {
  transform: scale(1.05);
}

/* Underline animation for product titles */
.hover-effect-underline {
  text-decoration: none;
  transition: text-decoration 0.3s ease;
}

.product-card:hover .hover-effect-underline {
  text-decoration: underline;
  text-decoration-thickness: 2px;
  text-underline-offset: 2px;
}

/* Responsive adjustments */
@media (max-width: 575.98px) {
  .product-card {
    max-width: 100%;
  }
  
  .btn-icon {
    width: 2rem;
    height: 2rem;
  }
}

/* Badge styling */
.badge {
  font-size: 0.75rem;
  font-weight: 600;
  padding: 0.375rem 0.5rem;
  border-radius: 0.25rem;
}

/* Star ratings */
.ci-star {
  font-size: 0.875rem;
}

/* Dropdown customization for mobile */
.dropdown-menu {
  --bs-dropdown-border-radius: 0.5rem;
  --bs-dropdown-box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.15);
}

/* Image container improvements */
.ratio-1x1 {
  --bs-aspect-ratio: 100%;
}

.object-fit-contain {
  object-fit: contain;
  width: 100%;
  height: 100%;
}

/* Card body adjustments */
.card-body {
  padding: 1rem 0.5rem;
}

@media (min-width: 768px) {
  .card-body {
    padding: 1rem;
  }
}
</style>