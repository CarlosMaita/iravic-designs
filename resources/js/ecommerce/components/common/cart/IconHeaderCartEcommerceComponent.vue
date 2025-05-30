<template>
    <button type="button" 
        class="btn btn-icon btn-lg fs-xl btn-outline-secondary position-relative border-0 rounded-circle animate-scale" 
        data-bs-toggle="offcanvas" 
        data-bs-target="#shoppingCart" 
        aria-controls="shoppingCart" 
        aria-label="Shopping cart">
        <span v-if="cartCount > 0" class="position-absolute top-0 start-100 badge fs-xs text-bg-primary rounded-pill mt-1 ms-n4 z-2" >
            {{cartCount}}
        </span>
        <i class="ci-shopping-cart animate-target me-1"></i>
    </button>
</template>
<script>

export default {
    name: 'IconHeaderCartEcommerceComponent',
    data() {
        return {
            cartCount: 0,
        };
    },
    methods: {
        setCartCount(count) {
            this.cartCount = count;
        },
    }
};
</script>
