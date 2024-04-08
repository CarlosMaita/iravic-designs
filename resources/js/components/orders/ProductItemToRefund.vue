<template>
    <tr>
        <td data-label="Venta">{{ item.orderProduct.order_id }}</td>
        <td data-label="Producto">{{ item.orderProduct.product_name }}</td>
        <td data-label="Código">{{ item.orderProduct.real_code }}</td>
        <td data-label="Descripción">
            <p class="mb-0">Género: {{ item.orderProduct.gender }}</p>
            <p v-if="item.orderProduct.brand" class="mb-0">Marca: {{ item.orderProduct.brand.name }}</p>
            <p v-if="item.orderProduct.brand" class="mb-0">Categoría: {{ item.orderProduct.category.name }}</p>
            <p v-if="item.orderProduct.brand" class="mb-0">Color: {{ item.orderProduct.color.name }}</p>
            <p v-if="item.orderProduct.brand" class="mb-0">Talla: {{ item.orderProduct.size.name }}</p>
        </td>
        <td data-label="Precio">{{ item.orderProduct.product_price_str }}</td>
        <td data-label="Crédito">{{ item.orderProduct.is_by_credit ? 'Si' : 'No' }}</td>
        <td data-label="Disponible para devolución">{{ item.orderProduct.available_for_refund }}</td>
        <td v-if="canRemove" data-label="Cantidad">
            <input :name="`qtys_refund[${item.orderProduct.id}]`" 
                    class="form-control input-product-refund-qty" 
                    type="number" 
                    step="1" 
                    min="0" 
                    :max="item.orderProduct.available_for_refund" 
                    v-model="quantity">

            <input type="hidden" 
                    name="products_refund[]"
                    :value="item.orderProduct.id">
        </td>
        <td v-if="!canRemove" data-label="Cantidad">
            {{ quantity }}
        </td>
        <td v-if="canRemove">
            <button type="button" 
                    @click="removeProduct(index)"
                    class="btn btn-sm btn-danger btn-action-icon" 
                    title="Eliminar" 
                    data-toggle="tooltip" 
                    style="width: auto;"
            >
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
    </tr>
</template>

<script>
    export default {
        components: {
        },
        props: {
            canRemove: {
                type: Boolean,
                default: true
            },
            item: {
                type: Object,
                default: null
            },
            index: {
                type: Number,
                default: -1
            }
        },
        data: () => ({
            quantity: 0
        }),
	    computed: {
	    },
        async mounted() {
            this.quantity = this.item.qty;
        },
        created() {
            this.$parent.$on("updateQuantityToRefund", (index,value) => {
                if(index == this.index){
                    this.quantity = value;
                }
            })
        },
        methods: {
            /**
             * Evento para eliminar producto. Emite evento 'removeProduct' recibido de su componente padre
             */
            removeProduct() {
                this.$emit('removeProduct', this.index);
            }
        },
        watch: {
            /**
             * Evento de cambio de valor de la cantidad.
             * Si la cantidad ingresa es superior a la cantidad maxima disponible para devolver, le setea el valor a dicha cantidad maxima
             */
            quantity: function(newVal, oldVal) {
                var newQty = Number(newVal),
                    max_available = Number(this.item.orderProduct.available_for_refund);

                if (newQty < 0 || isNaN(newQty))  {
                    this.quantity = 0;
                } else if (newQty > max_available) {
                    this.quantity = max_available;
                }

                this.$emit('updateQty', this.index, this.quantity);
            }
        }
    }
</script>

<style lang="scss">
</style>