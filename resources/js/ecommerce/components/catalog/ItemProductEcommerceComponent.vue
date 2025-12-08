<template>
     <!-- Item -->
    <div class="col-6 col-md-4 mb-2 mb-sm-3 mb-md-0">
        <div v-if="!imageLoaded">
            <product-card-skeleton-ecommerce-component />
        </div>
        <div v-show="imageLoaded" class="animate-underline hover-effect-opacity">
            <div class="position-relative mb-3">
            <button
                v-if="isAuthenticated"
                type="button"
                @click="toggleFavorite"
                :class="['btn btn-icon btn-secondary animate-pulse fs-base bg-transparent border-0 position-absolute top-0 end-0 z-2 mt-1 mt-sm-2 me-1 me-sm-2', {'text-danger': isFavorite}]"
                :aria-label="isFavorite ? 'Remover de favoritos' : 'Agregar a favoritos'"
                :disabled="favoriteLoading">
                <i :class="['animate-target', favoriteLoading ? 'ci-refresh spinner-border-sm' : (isFavorite ? 'ci-heart-filled' : 'ci-heart')]"></i>
            </button>
            <a class="d-flex bg-white border border-black rounded p-3" :href="product.url_detail">
                <div class="ratio" style="--cz-aspect-ratio: calc(308 / 274 * 100%)">
                <img class="object-fit-contain product-image-zoom" :src="(currentCombination ? currentCombination.url_thumbnail : product.url_thumbnail) || '/img/no_image.jpg'" alt="Image" @load="onImageLoad" @error="onImageLoad" style="display:block;" />
                </div>
            </a>
            <div v-if="!product.is_regular && currentCombination" class="hover-effect-target position-absolute start-0 bottom-0 w-100 z-2 opacity-0 pb-2 pb-sm-3 px-2 px-sm-3">
                <div class="d-flex align-items-center justify-content-center gap-2 gap-xl-3 bg-body rounded-2 p-2">
                    <span v-for="(size, index) in currentCombination.sizes" :key="index" class="fs-xs fw-medium text-secondary-emphasis py-1 px-sm-2">
                        {{size.size_name.toUpperCase()}}
                    </span>
                    <div class="nav">
                        <a class="nav-link fs-xs text-body-tertiary py-1 px-2" :href="product.url_detail">
                            +{{ currentCombination.sizes.length }}
                        </a>
                    </div>
                </div>
            </div>
            </div>
            <div class="nav mb-2">
            <a class="nav-link animate-target min-w-0 text-dark-emphasis p-0" :href="product.url_detail">
                <span class="text-truncate">{{ product.name }}</span>
            </a>
            </div>
            <div class="h6 mb-2" v-html="displayPrice"></div>

            <div v-if="!product.is_regular && product.combinations" class="position-relative">
                <div class="hover-effect-target fs-xs text-body-secondary opacity-100">Disponible en {{ product.combinations.length }} colores</div>
                <div class="hover-effect-target d-flex gap-2 position-absolute top-0 start-0 opacity-0">
                    <!-- Colors  -->
                    <div v-for="(combination, index) in product.combinations" :key="index" >
                        <input @input="selectCombinancion(combination)"
                            :value="combination.id" type="radio" class="btn-check"
                            :name="`colors-${product.id}`"
                            :id="`colors-${ product.id }-${ combination.id}`"
                            :checked="combination.id === currentCombination.id" />
                        <label :for="`colors-${ product.id }-${ combination.id}`" class="btn btn-color fs-base" :style="`color: ${combination.color_code}`">
                        <span class="visually-hidden">{{ combination.text_color }}</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import ProductCardSkeletonEcommerceComponent from './ProductCardSkeletonEcommerceComponent.vue';
export default {
  name: 'ItemProductEcommerceComponent',
  components: {
    ProductCardSkeletonEcommerceComponent
  },
  props: {
    product: {
      // Define the product prop with the expected type
      type: Object,
      required: true,
    }
  },
  data() {
    return {
      currentCombination: this.product.combinations ? this.product.combinations[0] : null, // Initialize currentCombination to null,
      imageLoaded: false,
      isFavorite: false,
      favoriteLoading: false,
      isAuthenticated: false
    };
  },
  computed: {
    displayPrice() {
      const priceValue = this.product.regular_price || 0;
      return '$' + priceValue.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    }
  },
  async mounted() {
    // Set the default combination when the component is mounted
    if (this.product && this.product.combinations && this.product.combinations.length > 0 && !this.product.is_regular) {
      this.currentCombination = this.product.combinations[0];
    }

    // Check authentication status and favorite status
    await this.checkAuthAndFavoriteStatus();

    // If there's no image to load, set imageLoaded to true immediately
    const imageUrl = this.currentCombination ? this.currentCombination.url_thumbnail : this.product.url_thumbnail;
    if (!imageUrl) {
      this.imageLoaded = true;
    }

  },
  methods: {
    // Add any methods you need here
    selectCombinancion(combination) {
      this.currentCombination = combination;
      this.imageLoaded = false;

      // If the new combination has no image, set imageLoaded to true immediately
      if (!combination.url_thumbnail) {
        this.imageLoaded = true;
      }
    },
    onImageLoad() {
      this.imageLoaded = true;
    },

    async checkAuthAndFavoriteStatus() {
      try {
        const response = await fetch('/api/customer/auth-check', {
          credentials: 'same-origin'
        });
        const data = await response.json();
        this.isAuthenticated = data.authenticated;

        // If authenticated, check if this product is in favorites
        if (this.isAuthenticated) {
          await this.checkFavoriteStatus();
        }
      } catch (error) {
        console.error('Error checking auth status:', error);
        this.isAuthenticated = false;
      }
    },

    async checkFavoriteStatus() {
      try {
        // We can check if the product is in favorites by trying to fetch customer favorites
        // For now, we'll set it to false and update when we actually toggle
        this.isFavorite = false;
      } catch (error) {
        console.error('Error checking favorite status:', error);
      }
    },

    async toggleFavorite() {
      if (!this.isAuthenticated) {
        // Redirect to login
        window.location.href = '/ingresar';
        return;
      }

      this.favoriteLoading = true;

      try {
        const response = await fetch('/api/favorites/toggle', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': (function(){
              var el = document.querySelector('meta[name="csrf-token"]');
              var content = el && el.getAttribute('content');
              return content || (window.Laravel && window.Laravel.csrfToken) || '';
            })(),
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({
            product_id: this.product.id
          }),
          credentials: 'same-origin'
        });

        const data = await response.json();

        if (data.success) {
          this.isFavorite = data.is_favorite;
          // Show success message using parent component's toast if available
          this.$emit('show-toast', data.message, 'success');
        } else {
          this.$emit('show-toast', data.message || 'Error al actualizar favoritos', 'error');
        }
      } catch (error) {
        console.error('Error toggling favorite:', error);
        this.$emit('show-toast', 'Error de conexión. Inténtalo de nuevo.', 'error');
      } finally {
        this.favoriteLoading = false;
      }
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
