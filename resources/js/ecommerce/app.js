/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../bootstrap');

import 'vue-select/dist/vue-select.css';

window.Vue = require('vue');

Vue.prototype.$axios = window.axios;


// Vue.use(vueble)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Common Ecommerce components
Vue.component('cart-ecommerce-component', require('../ecommerce/components/common/cart/CartEcommerceComponent.vue').default);
Vue.component('item-cart-ecommerce-component', require('../ecommerce/components/common/cart/ItemCartEcommerceComponent.vue').default);
Vue.component('shipping-modal-component', require('../ecommerce/components/common/cart/ShippingModalComponent.vue').default);
Vue.component('toast-ecommerce-component', require('../ecommerce/components/common/ToastEcommerceComponent.vue').default);
Vue.component('icon-header-cart-ecommerce-component', require('../ecommerce/components/common/cart/IconHeaderCartEcommerceComponent.vue').default);

// Catalog Ecommerce components
Vue.component('catalog-ecommerce-component', require('../ecommerce/components/catalog/CatalogEcommerceComponent.vue').default);
Vue.component('grid-products-ecommerce-component', require('../ecommerce/components/catalog/GridProductsEcommerceComponent.vue').default);
Vue.component('item-product-ecommerce-component', require('../ecommerce/components/catalog/ItemProductEcommerceComponent.vue').default);
Vue.component('filter-ecommerce-component', require('../ecommerce/components/catalog/FilterEcommerceComponent.vue').default);
Vue.component('range-price-filter-ecommerce-component', require('../ecommerce/components/catalog/RangePriceFilterEcommerceComponent.vue').default);

// Product Detail Ecommerce components
Vue.component('product-detail-ecommerce-component', require('../ecommerce/components/product-detail/ProductDetailEcommerceComponent.vue').default);
Vue.component('product-detail-images-ecommerce-component', require('../ecommerce/components/product-detail/ProductDetailImagesEcommerceComponent.vue').default);
Vue.component('product-detail-description-ecommerce-component', require('../ecommerce/components/product-detail/ProductDetailDescriptionEcommerceComponent.vue').default);

// Skeletons ecommerce components
Vue.component('product-detail-skeleton-ecommerce-component', require('../ecommerce/components/product-detail/ProductDetailSkeletonEcommerceComponent.vue').default);
Vue.component('product-card-skeleton-ecommerce-component', require('../ecommerce/components/catalog/ProductCardSkeletonEcommerceComponent.vue').default);

// Home ecommerce components
Vue.component('featured-products-carousel-ecommerce-component', require('../ecommerce/components/home/FeaturedProductsCarouselEcommerceComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const app = new Vue({
    el: '#app',
    data: {
        // Puedes añadir propiedades de datos aquí si tu buscador lo necesita,
        // por ejemplo, para almacenar el término de búsqueda:
        // searchTerm: ''
    },
    mounted() {
        // Esta función se ejecuta después de que la instancia de Vue ha sido montada
        const searchOffcanvas = document.getElementById('searchBox');
        // Accede al input de búsqueda directamente usando un selector CSS
        const searchInput = document.querySelector('#searchBox input[type="search"]');

        if (searchOffcanvas && searchInput) {
            // Escucha el evento 'shown.bs.offcanvas' de Bootstrap
            searchOffcanvas.addEventListener('shown.bs.offcanvas', () => {
                // Usa Vue.nextTick() para asegurarte de que el DOM esté completamente actualizado
                // antes de intentar hacer focus.
                this.$nextTick(() => { // 'this' aquí se refiere a la instancia de Vue
                    searchInput.focus();
                });
            });
        }
    },
});