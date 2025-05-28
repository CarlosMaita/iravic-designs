<template>
     <!-- Item -->
    <div class="col-6 col-md-4 mb-2 mb-sm-3 mb-md-0">
        <div class="animate-underline hover-effect-opacity">
            <div class="position-relative mb-3">
            <button type="button" class="btn btn-icon btn-secondary animate-pulse fs-base bg-transparent border-0 position-absolute top-0 end-0 z-2 mt-1 mt-sm-2 me-1 me-sm-2" aria-label="Add to Wishlist">
                <i class="ci-heart animate-target"></i>
            </button>
            <a class="d-flex bg-white border border-black rounded p-3" :href="product.url_detail">
                <div class="ratio" style="--cz-aspect-ratio: calc(308 / 274 * 100%)">
                <img class="object-fit-contain" :src="currentCombination ? currentCombination.url_thumbnail : product.url_thumbnail" alt="Image">
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
            <div class="h6 mb-2">{{ product.regular_price_str }}</div>

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
export default {
    name: 'ItemProductEcommerceComponent',
    props: {
        product: {
            // Define the product prop with the expected type
            type: Object,
            required: true,
        }
    },
    data() {
        return {
            currentCombination: this.product.combinations ? this.product.combinations[0] : null // Initialize currentCombination to null,
        };
    },
    mounted() {
        // Set the default combination when the component is mounted
        if (this.product && this.product.combinations && this.product.combinations.length > 0 && !this.product.is_regular) {
             this.currentCombination = this.product.combinations[0];
        }
    },
    methods: {
        // Add any methods you need here
        selectCombinancion(combination){
            this.currentCombination = combination;
        }
    }
};
</script>