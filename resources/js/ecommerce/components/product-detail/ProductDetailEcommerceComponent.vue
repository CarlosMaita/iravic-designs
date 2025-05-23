<template>
      <!-- Product gallery and details -->
      <section class="container">
        <div class="row">
            <!-- Product images(carrousel) -->
             <product-detail-images-ecommerce-component  ref="productDetailImages" ></product-detail-images-ecommerce-component>

            <!-- Product details and options -->
            <product-detail-description-ecommerce-component ref="productDetailDescription" 
                :id='product.id'
                :name='product.name'
                :description="product.description" 
                :price="product.price"
                :price_str="product.price_str"
                :is_regular="product.is_regular ? true : false"
                :url_detail="product.url_detail"
                :url_thumbnail="product.url_thumbnail"
                :total_stock="product.stock_total ? product.stock_total : 0"
                :combinations="product.combinations"
                @combination-selected="selectCombination"
                 ></product-detail-description-ecommerce-component>
            
        </div>
      </section>
</template>
<script>
    export default {
        components: {
        },
        props: {
            product: {
                type: Object,
                required: true,
            }   
        },
        mounted() {
            console.log(this.product)
            // set images in the product detail images component
          if (this.product.is_regular){
             this.$refs.productDetailImages.setImages(this.product.images);
          }
        },
        data() {
            return {
                totalStock: 0,
                currentCombination : null,
            };
        },
        methods: {
            selectCombination(combination){ 
                // modificar la combincion en el componente de imagenes 
                this.$refs.productDetailImages.setImages(combination.images);
            }
        }   
    }
</script>