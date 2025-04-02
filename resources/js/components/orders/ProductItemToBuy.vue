<template>
    <tr>
        <td data-label="Producto">{{ item.product.name }}</td>
        <td data-label="Código">{{ item.product.real_code }}</td>
        <td data-label="Descripción">
            <p class="mb-0">Genero: {{ item.product.gender }}</p>
            <p v-if="item.product.brand" class="mb-0">Marca: {{ item.product.brand.name }}</p>
            <p v-if="item.product.category" class="mb-0">Categoría: {{ item.product.category.name }}</p>
            <p v-if="item.product.color" class="mb-0">Color: {{ item.product.color.name }}</p>
            <p v-if="item.product.size" class="mb-0">Talla: {{ item.product.size.name }}</p>
        </td>
        <td data-label="Precio">{{ item.product.regular_price_str }}</td>
        <td data-label="Disponible">{{ item.product.stock_user }}</td>
        <td v-if="canRemove" data-label="Cantidad">
            <input :name="`qtys[${item.product.id}][${item.store_id}]`" 
                    class="form-control" 
                    type="number" 
                    step="1" 
                    min="0" 
                    :max="item.product.stock_user" 
                    v-model="quantity">

            <input type="hidden" 
                    name="products[${item.product.id}]"
                    :value="item.product.name">
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
            this.$parent.$on("updateQuantityToBuy", (index,value) => {
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
             * Si la cantidad ingresa es superior a la cantidad maxima disponible para comprar, le setea el valor a dicha cantidad maxima
             */
            quantity: function(newVal, oldVal) {
                var newQty = Number(newVal),
                    max_available = Number(this.item.product.stock_user);

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