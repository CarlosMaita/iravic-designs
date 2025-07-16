<template>
    <div class="catalog-ecommerce">
        <!-- {{-- Page title -->
        <h1 class="h3 container pb-3 pb-lg-4">Catalogo de tienda</h1>

        <!-- Products grid + Sidebar with filters -->
        <section class="container">
            <div class="row">

                <!-- filter Ecommerce -->
                <filter-ecommerce-component  
                    :categories="categories" 
                    :brands="brands"
                    :genders="genders"
                    :colors="colors"
                    :search="search"
                    :category="category"
                    @filter-applied="setFilters"
                    ></filter-ecommerce-component>
                
                <!-- Grid products -->
                <grid-products-ecommerce-component ref="gridProducts" ></grid-products-ecommerce-component>
                
            </div>
        </section>
    </div>
</template>
<script>
    export default {
        components: {
        },
        props: {
            categories: {
                type: Array,
                default: () => [],
            },
            brands: {
                type: Array,
                default: () => [],
            },
            genders: {
                type: Array,
                default: () => [],
            },
            sizes: {
                type: Array,
                default: () => [],
            },
            colors: {
                type: Array,
                default: () => [],
            },
            search : {
                type: String,
                default: '',
            },
            category : {
                type: String,
                default: '',
            },
        },
        mounted(){
               
        },
        data() {
            return {
              
            };
        },
        methods:{
            // Add any methods you need here
            setFilters(filters) {
                this.$refs.gridProducts.setFilters(filters);
            },
        }
    }
</script>