<template>
    <!-- modal-backdrop show -->
    <div id="modal-product" class="modal" ref="modalProductToRefund" tabindex="-1" role="dialog">
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
                                    <p class="product-category font-weight-normal">{{ product.category_name }}</p>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-group">
                                    <label><b>Marca</b></label>
                                    <p class="product-brand font-weight-normal">{{ product.brand_name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="product-add-stocks">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-refund">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID venta</th>
                                            <th scope="col">Color</th>
                                            <th scope="col">Talla</th>
                                            <th scope="col">Comprado</th>
                                            <th scope="col">Devolver</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(orderProduct) in product.order_products" :key="`tr-refund-${orderProduct.id}`">
                                            <td data-label="ID venta">{{ orderProduct.order_id }}</td>
                                            <td data-label="Color">{{ orderProduct.color ? orderProduct.color.name : '' }}</td>
                                            <td data-label="Talla">{{ orderProduct.size ? orderProduct.size.name : '' }}</td>
                                            <td data-label="Comprado">{{ orderProduct.available_for_refund }}</td>
                                            <td data-label="Devolver">
                                                <div class="form-group">
                                                    <input v-model="qty[orderProduct.id]" 
                                                        autocomplete="off"
                                                        class="form-control modal-product-refund-input" 
                                                        type="number" 
                                                        min="0" 
                                                        step="1" 
                                                        :max="orderProduct.available_for_refund">
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button @close="closeModal" class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
                    <button @click="addProductToRefund" class="btn btn-primary" type="button">Agregar</button>
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
            qty: []
        }),
	    computed: {
	    },
        async mounted() {
        },
        methods: {
            /**
             * Agrega producto al listado de productos para devolucion
             * Se valida que las cantidades ingresadas no sean superiores a las disponibles para devolver
             */
            addProductToRefund() {
                var error = false,
                    products_to_refund = []
                    self = this;

                this.product.order_products.forEach((orderProduct, index) => {
                    var qty = self.qty[orderProduct.id],
                        max = orderProduct.available_for_refund;

                    if (qty > 0 && qty <= max) {
                        var dataToAdd = {
                            id: orderProduct.id,
                            orderProduct: orderProduct,
                            qty: Number(qty)
                        }

                        products_to_refund.push(dataToAdd);
                    } else if (qty > max) {
                        error = true;

                        new Noty({
                            text: "La cantidad a devolver para el producto " + (index + 1) + " es mayor a la que puede devolver.",
                            type: 'error'
                        }).show();
                    }
                });

                if (products_to_refund.length) {
                    this.$emit('addProduct', products_to_refund);
                    this.closeModal();
                } else if (!error) {
                    new Noty({
                        text: "Debe agregar cantidades a retornar.",
                        type: 'error'
                    }).show();
                }
            },
            /**
             * Cierra el modal
             */
            closeModal() {
                this.product = null;
                $(this.$refs.modalProductToRefund).modal('hide');
            },
            /**
             * Abre el modal
             */
            showModal(product) {
                this.product = product;
                $(this.$refs.modalProductToRefund).modal('show');
            }
        },
        watch: { 
            /**
             * Evento de cambio de valor de la cantidad.
             * Si la cantidad ingresa es superior a la cantidad maxima disponible para devolver, le setea el valor a dicha cantidad maxima
             */
            qty: function(value) {
                var newQty = 0,
                    id = null,
                    index = null,
                    max_available = 0,
                    orderProduct = null;

                for (var key in value) {
                    newQty = Number(value[key]);
                    id = key;
                    index = this.product.order_products.findIndex(_item => _item.id == id);
                }

                if (index > -1) {
                    orderProduct = this.product.order_products[index];
                    max_available = orderProduct.available_for_refund;

                    if (newQty < 0 || isNaN(newQty))  {
                        this.qty[id] = 0;
                    } else if (newQty > max_available) {
                        this.qty[id] = max_available;
                    }
                }
            }
        }
    }
</script>

<style lang="scss">
</style>