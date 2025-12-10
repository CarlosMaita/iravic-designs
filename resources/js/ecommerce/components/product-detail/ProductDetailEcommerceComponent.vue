<template>
      <!-- Product gallery and details -->
      <section class="container">
        <div class="row">
          <template v-if="loading">
            <product-detail-skeleton-ecommerce-component />
          </template>
          <template v-else>
            <!-- Product images(carrousel) -->
            <product-detail-images-ecommerce-component  ref="productDetailImages" ></product-detail-images-ecommerce-component>

            <!-- Product details and options -->
            <product-detail-description-ecommerce-component ref="productDetailDescription"
                :id="loadedProduct.id"
                :name="loadedProduct.name"
                :description="loadedProduct.description"
                :price="loadedProduct.price"
                :price_str="loadedProduct.price_str"
                :is_regular="loadedProduct.is_regular ? true : false"
                :url_detail="loadedProduct.url_detail"
                :url_thumbnail="loadedProduct.url_thumbnail"
                :total_stock="loadedProduct.stock_total ? loadedProduct.stock_total : 0"
                :combinations="loadedProduct.combinations"
                @combination-selected="selectCombination"
                 ></product-detail-description-ecommerce-component>
          </template>
        </div>
      </section>
</template>
<script>
export default {
  props: {
    product: {
      type: Object,
      required: false,
      default: null
    }
  },
  data() {
    return {
      totalStock: 0,
      currentCombination: null,
      loading: true,
      loadedProduct: null
    };
  },
  mounted() {
    // Siempre mostrar el skeleton por 1 segundo antes de mostrar el detalle
    this.loading = true;
    setTimeout(() => {
      this.loadedProduct = this.product;
      this.loading = false;
      // Esperar a que el child con ref `productDetailImages` esté montado
      this.$nextTick(() => {
        if (this.product && this.product.is_regular) {
          this.setGalleryImages(this.product.images);
        }
      });
    }, 1000);
  },
  methods: {
    setGalleryImages(images) {
      const hasImages = Array.isArray(images) && images.length > 0;
      const fallbackImages = (this.product && Array.isArray(this.product.images)) ? this.product.images : [];
      const finalImages = hasImages ? images : fallbackImages;

      const applyImages = () => {
        try {
          if (this.$refs.productDetailImages) {
            this.$refs.productDetailImages.setImages(finalImages || []);
          }
        } catch (e) {
          // no-op: evita romper la UI si aún no existe el ref
        }
      };

      if (this.$refs.productDetailImages) {
        applyImages();
      } else {
        this.$nextTick(applyImages);
      }
    },
    fetchProductDetail() {
      // Simula una petición asíncrona, reemplaza con tu lógica real
      setTimeout(() => {
        // Aquí deberías obtener el producto real, por ahora simula con un objeto vacío
        this.loadedProduct = {};
        this.loading = false;
      }, 1200);
    },
    selectCombination(combination) {
      const images = combination && combination.images ? combination.images : [];
      this.setGalleryImages(images);
    }
  }
}
</script>
