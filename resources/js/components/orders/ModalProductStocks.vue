<template>
    <!-- modal-backdrop show -->
    <div id="modal-product" class="modal" ref="modalProductStock" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Formulario Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div v-if="product" class="modal-body text-left">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-6 col-md-3">
                                <div class="form-group">
                                    <label><b>Nombre</b></label>
                                    <p class="product-name font-weight-normal">{{ product.name }}</p>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-group">
                                    <label><b>Código</b></label>
                                    <p class="product-code font-weight-normal">{{ product.code }}</p>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-group">
                                    <label><b>Categoría</b></label>
                                    <p class="product-category font-weight-normal">{{ product.category ? product.category.name : ''}}</p>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-group">
                                    <label><b>Marca</b></label>
                                    <p class="product-brand font-weight-normal">{{ product.brand ? product.brand.name : ''}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-refund">
                                        <thead>
                                            <tr>
                                                <th scope="col" v-if="product.color" style="width: 16%;">Color</th>
                                                <th scope="col" v-if="product.size" style="width: 16%;">Talla</th>
                                                <th scope="col" style="width: 16%;">Precio</th>
                                                <th scope="col" style="width: 16%;">Déposito</th>
                                                <th scope="col" style="width: 16%;">Stock</th>
                                                <th scope="col" style="width: 16%;">Cantidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(store, index) in product.stores.filter( store => store.pivot.stock > 0)" :key="index">
                                                <td v-if="!product.is_regular && product.color" data-label="Color">{{ product.color.name }}</td>
                                                <td v-if="!product.is_regular && product.size" data-label="Talla">{{ product.size.name }}</td>
                                                <td data-label="Precio">{{ product.regular_price_str }}</td>
                                                <td data-label="Déposito">{{ store ? store.name : '' }}</td>
                                                <td data-label="Stock">{{ store.pivot.stock }}</td>
                                                <td data-label="Cantidad">
                                                    <div class="form-group">
                                                        <input v-model="qty[store.id]"
                                                            class="form-control" 
                                                            type="number"
                                                            min="0" 
                                                            step="1" 
                                                            :max="store.pivot.stock" 
                                                            value="1"
                                                        >
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!--  -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button @close="closeModal" class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
                    <button @click="addProductToOrder" class="btn btn-primary" type="button">Agregar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
        },
        props: {
        },
        data: () => ({
            product: null,
            qty: [],
        }),
	    computed: {
	    },
        async mounted() {
        },
        methods: {
            /**
             * Agrega producto al listado de productos para a llevarse
             * Se valida que las cantidades ingresadas no sean superiores a las disponibles para comprar
             */
            addProductToOrder() {
                var error = false,
                    products_to_order = [];
                    self = this;

                // var data = {};
                this.product.stores.forEach((storeProduct, index) => {
                    var qty = self.qty[storeProduct.id],
                        max = storeProduct.pivot.stock;

                    if (qty > 0 && qty <= max) {
                        var dataToAdd = {
                            id: this.product.id,
                            store_id: storeProduct.id,
                            product: storeProduct,
                            qty: Number(qty)
                        }
                        products_to_order.push(dataToAdd);
                    } else if (qty > max) {
                        error = true;
                        new Noty({
                            text: "La cantidad ingresada supera el stock disponible del producto.",
                            type: 'error'
                        }).show();
                    }
                });

                if(products_to_order.length) {
                    this.$emit('addProduct', products_to_order);
                    this.closeModal();
                } else if (!error) {
                    new Noty({
                        text: "Debe ingresar una cantidad.",
                        type: 'error'
                    }).show();
                }
            },

            /**
             * Cierra el modal
             */
            closeModal() {
                this.product = null;
                $(this.$refs.modalProductStock).modal('hide');
            },

            /**
             * Abre el modal
             */
            showModal(product) {
                this.product = product;
                $(this.$refs.modalProductStock).modal('show');
            }
        },
        watch: { 
            /**
             * Evento de cambio de valor de la cantidad.
             * Si la cantidad ingresa es superior a la cantidad maxima disponible de stock asociado al usuario, le setea el valor a dicha cantidad maxima
             */
            qty : function(value) {
                var newQty = 0,
                    id = null,
                    index = null,
                    max_available = 0;

                for (var key in value){
                    newQty = Number(value[key]);
                    id = key;
                    index = this.product.stores.findIndex(store => store.id == id);
                    max_available = this.product.stores[index].pivot.stock;
                }

                if (newQty < 0 || isNaN(newQty))  {
                    this.qty[id] = 0;
                } else if (newQty > max_available) {
                    this.qty[id] = max_available;
                }
            }
        }
    }
</script>

<style lang="scss">
</style>