<template>
     <!-- Product grid -->
    <div class="col-lg-9">

        <!-- Sorting -->
        <div class="d-flex align-items-center justify-content-between mt-n2 mb-3 mb-sm-4">
            <div class="fs-sm text-body-emphasis text-nowrap">
                <span class="fw-semibold">{{totalProductCount}}</span> resultados
            </div>
            <div class="d-flex align-items-center text-nowrap">
                <label class="form-label fw-semibold mb-0 me-2">Ordenar por:</label>
                <div style="width: 130px">
                    <select v-model="sorting" 
                        class="form-select border-0 rounded-0 px-1" data-select='{
                        "removeItemButton": false,
                        "classNames": {
                            "containerInner": ["form-select", "border-0", "rounded-0", "px-1"]
                        }
                        }'>
                    <option value="newest">Mas recientes</option>
                    <option value="price-asc">Menor precio</option>
                    <option value="price-desc">Mayor precio</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row gy-4 gy-md-5 pb-4 pb-md-5">
            <!-- Skeletons -->
            <template v-if="loading">
                <div v-for="n in 8" :key="'skeleton-'+n" class="col-6 col-md-4 col-lg-3">
                    <product-card-skeleton-ecommerce-component />
                </div>
            </template>
            <!-- items product ecommerce -->
            <template v-else>
                <div v-if="products.length == 0" class="text-center">
                    <svg class="text-body-tertiary opacity-60 mb-4" xmlns="http://www.w3.org/2000/svg" width="60" viewBox="0 0 512 512" fill="currentColor"><path d="M340.115,361.412l-16.98-16.98c-34.237,29.36-78.733,47.098-127.371,47.098C87.647,391.529,0,303.883,0,195.765S87.647,0,195.765,0s195.765,87.647,195.765,195.765c0,48.638-17.738,93.134-47.097,127.371l16.98,16.98l11.94-11.94c5.881-5.881,15.415-5.881,21.296,0l112.941,112.941c5.881,5.881,5.881,15.416,0,21.296l-45.176,45.176c-5.881,5.881-15.415,5.881-21.296,0L328.176,394.648c-5.881-5.881-5.881-15.416,0-21.296L340.115,361.412z M195.765,361.412c91.484,0,165.647-74.163,165.647-165.647S287.249,30.118,195.765,30.118S30.118,104.28,30.118,195.765S104.28,361.412,195.765,361.412z M360.12,384l91.645,91.645l23.88-23.88L384,360.12L360.12,384z M233.034,233.033c5.881-5.881,15.415-5.881,21.296,0c5.881,5.881,5.881,15.416,0,21.296c-32.345,32.345-84.786,32.345-117.131,0c-5.881-5.881-5.881-15.415,0-21.296c5.881-5.881,15.416-5.881,21.296,0C179.079,253.616,212.45,253.616,233.034,233.033zM135.529,180.706c-12.475,0-22.588-10.113-22.588-22.588c0-12.475,10.113-22.588,22.588-22.588c12.475,0,22.588,10.113,22.588,22.588C158.118,170.593,148.005,180.706,135.529,180.706z M256,180.706c-12.475,0-22.588-10.113-22.588-22.588c0-12.475,10.113-22.588,22.588-22.588s22.588,10.113,22.588,22.588C278.588,170.593,268.475,180.706,256,180.706z"/></svg>
                    <h3 class="h5 mt-3">No encontramos productos que coincidan con tu busqueda</h3>
                    <p class="fs-sm text-body">Intenta con otros terminos de busqueda</p>
                </div>
                <item-product-ecommerce-component v-for="product in products" :key="product.id" :product="product"></item-product-ecommerce-component>
            </template>
        </div>
            
        <!-- Show more button -->
        <button v-if="pagination.currentPage < pagination.totalPages" @click="loadProducts(pagination.nextPage, sorting)" type="button" class="btn btn-lg btn-outline-secondary w-100" :disabled="loading">
            <template v-if="loading">
                Loading...
                <span class="spinner-border spinner-border-sm ms-n1 me-2" role="status" aria-hidden="true"></span>
            </template>
            <template v-else>
                Show more
                <i class="ci-chevron-down fs-xl ms-2 me-n1"></i>
            </template>
        </button>
    </div>
</template>
<script>
import Axios from 'axios';

export default {
    name: 'GridProductsEcommerceComponent',
    props: {
    },
    data() {
            return {
                // Add any data properties you need here
                products: [],
                loading: false,
                apiUrl : '/api/products',
                sorting: 'newest',
                totalProductCount: 0,
                // Pagination data
                pagination: {
                    currentPage: 1,
                    nextPage: 2,
                    totalPages: 5,
                },
                filters: {},
                search: '',
            };
        },
    mounted(){
      
    }, 
    watch: {
        sorting(newValue){
            this.loadProducts();   
        }
    },
    methods: {
        async loadProducts(page = 1) {
            this.loading = true;
            this.products = page === 1 ? [] : this.products; //reset products if page is 1

            try {
                const newProducts = await this.getApiProducts(page, this.sorting, this.filters);
                this.products = [...this.products, ...newProducts];
                this.pagination.currentPage = page;
                this.pagination.nextPage = page + 1;
            } catch (error) {
                console.error('Error loading products:', error);
            } finally {
                this.loading = false;
            }
        },
        async getApiProducts(page, sorting, filters = {}) {
            try {
                const url = `${this.apiUrl}?page=${page}&sorting=${sorting}&category=${filters.category || ''}&gender=${filters.gender || ''}&brand=${filters.brand || ''}&color=${filters.color || ''}&min_price=${filters.minPrice || ''}&max_price=${filters.maxPrice || ''}&search=${filters.search || ''}`;
                const response = await Axios.get(url);
                this.totalProductCount = response.data.totalProductCount;
                this.pagination.totalPages = response.data.totalPages;
                return response.data.products || [];
            } catch (error) {
                console.error('Error fetching products:', error);
                return [];
            }
        },
        setFilters(filters) {
            this.filters = filters;
            this.loadProducts();
        },

    }
};
</script>