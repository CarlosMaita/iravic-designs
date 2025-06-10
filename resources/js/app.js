/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import vSelect from 'vue-select'

import 'vue-select/dist/vue-select.css';

import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'

import Chart  from 'chart.js';

// import vueble from 'vueble'

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

// catalog components
Vue.component('product-form', require('./components/catalog/ProductFormComponent.vue').default);
// stock components
Vue.component('store-form', require('./components/stock/StoreFormComponent.vue').default);
// orders components
Vue.component('devolution-form', require('./components/orders/Devolution.vue').default);
Vue.component('modal-discount', require('./components/orders/ModalDiscount.vue').default);
Vue.component('modal-add-producto-stock', require('./components/orders/ModalProductStocks.vue').default);
Vue.component('modal-producto-to-refund', require('./components/orders/ModalProductToRefund.vue').default);
Vue.component('product-item-to-refund', require('./components/orders/ProductItemToRefund.vue').default);
Vue.component('product-item-to-buy', require('./components/orders/ProductItemToBuy.vue').default);
Vue.component('credit-information', require('./components/orders/CreditInformationComponent.vue').default);
Vue.component('frequency-collection', require('./components/orders/FrequencyCollectionComponent.vue').default);
// inventory components
Vue.component('import-inventory', require('./components/inventory/ImportInventoryComponent.vue').default);
// credit components
Vue.component('credit-show', require('./components/credits/CreditShowComponent.vue').default);
Vue.component('credit-form', require('./components/credits/CreditFormComponent.vue').default);


// global components
Vue.component('v-select', vSelect)
Vue.component('v-dropzone', vue2Dropzone)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 Vue.filter('toCurrency', function (value) {
    if (typeof value !== "number") {
        return value;
    }
    var formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2
    });
    return formatter.format(value);
});

const app = new Vue({
    el: '#app',
});
