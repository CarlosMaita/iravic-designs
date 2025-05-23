<template>
      <!-- Item Cart -->
        <div class="d-flex align-items-center mb-2">
          <a class="flex-shrink-0" :href="item.url">
            <img :src="item.image" class="bg-body-tertiary rounded" width="110" alt="Thumbnail">
          </a>
          <div class="w-100 min-w-0 ps-3">
            <h5 class="d-flex animate-underline mb-2">
              <a class="d-block fs-sm fw-medium text-truncate animate-target" :href="item.url">{{item.name}}</a>
            </h5>
            <div class="h6 pb-1 mb-2">{{item.price_str}}</div>
            <div class="mb-2">
              <span v-if="item.color" class="badge rounded-pill bg-dark text-light me-2">{{item.color}}</span>
              <span v-if="item.size" class="badge rounded-pill bg-dark text-light">{{item.size}}</span>
            </div>
            <div class="d-flex align-items-center justify-content-between">
              <div class="count-input rounded-2">
                <button @click="decrementQuantity()" type="button" class="btn btn-icon btn-sm"  aria-label="Disminuir cantidad">
                  <i class="ci-minus"></i>
                </button>
                <input v-model="item.quantity" type="number" class="form-control form-control-sm" readonly>
                <button @click="incrementQuantity()" type="button" class="btn btn-icon btn-sm"  aria-label="Incrementar cantidad">
                  <i class="ci-plus"></i>
                </button>
              </div>
              <button @click="removeItem()" type="button" class="btn-close fs-sm"  aria-label="Eliminar del carrito"></button>
            </div>
          </div>
        </div>
        <!-- End Item Cart  -->
</template>
<script>

export default {
    props: {
      item: {
        type: Object,
        required: true
      },
    },
    methods: {
      incrementQuantity() {
        this.item.quantity++;
        this.$emit('update-quantity', this.item);
      },
      decrementQuantity() {
        if (this.item.quantity > 1) {
          this.item.quantity--;
        }
        this.$emit('update-quantity', this.item);
      },
      removeItem() {
        // For example, emit an event to the parent component
        this.$emit('remove-item', this.item);
      }
    }
}
</script>
