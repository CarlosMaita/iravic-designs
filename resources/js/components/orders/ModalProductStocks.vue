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
                                            <tr v-if="product.is_regular">
                                                <th scope="col" style="width: 33%;">Precio</th>
                                                <th scope="col" style="width: 33%;">Stock</th>
                                                <th scope="col" style="width: 33%;">Cantidad</th>
                                            </tr>
                                            <tr v-else>
                                                <th v-if="product.color" scope="col" style="width: 20%;">Color</th>
                                                <th v-if="product.size" scope="col" style="width: 20%;">Talla</th>
                                                <th scope="col" style="width: 20%;">Precio</th>
                                                <th scope="col" style="width: 20%;">Stock</th>
                                                <th scope="col" style="width: 20%;">Cantidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-if="product.is_regular">
                                                <td data-label="Precio">{{ product.regular_price_str }}</th>
                                                <td data-label="Stock">{{ product.stock_user }}</td>
                                                <td data-label="Cantidad">
                                                    <div class="form-group">
                                                        <input v-model="qty" 
                                                            class="form-control" 
                                                            type="number" 
                                                            min="0" 
                                                            step="1" 
                                                            :max="product.stock_user" 
                                                            value="1"
                                                        >
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr v-else>
                                                <td data-label="Color" v-if="product.color">{{ product.color.name }}</td>
                                                <td data-label="Talla" v-if="product.size">{{ product.size.name }}</td>
                                                <td data-label="Precio">{{ product.regular_price_str }}</td>
                                                <td data-label="Stock">{{ product.stock_user }}</td>
                                                <td data-label="Cantidad">
                                                    <div class="form-group">
                                                        <input  v-model="qty" 
                                                            class="form-control" 
                                                            type="number" 
                                                            min="0" 
                                                            step="1" 
                                                            :max="product.stock_user" value="1"
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
                        <div class="row">
                            <div class="col-12">
                                <p class="text-right">
                                    <button v-if="!collapseShow" @click="openCollapseStocks" class="btn btn-link" type="button" aria-expanded="false">
                                        <i class="fa fa-eye" aria-hidden="true"></i> Ver todos los stocks
                                    </button>
                                    <button v-else @click="closeCollapseStocks" class="btn btn-link" type="button" aria-expanded="false">
                                        <i class="fa fa-eye-slash" aria-hidden="true"></i> Cerrar stocks
                                    </button>
                                </p>
                                <div class="collapse" :class="{ 'show' : collapseShow }">
                                    <div class="card card-body px-0">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-12 px-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-refund mb-0" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col" class="text-center">Depósito</th>
                                                                    <th scope="col" class="text-center">Local</th>
                                                                    <th scope="col" class="text-center">Camión</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td data-label="Depósito">{{ product.stock_depot }}</td>
                                                                    <td data-label="Local">{{ product.stock_local }}</td>
                                                                    <td data-label="Camión">{{ product.stock_truck }}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
            collapseShow: false,
            product: null,
            qty: 0
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
                var data = {};

                if (this.qty && !isNaN(this.qty) && this.qty > 0) {
                    if (this.qty > this.product.stock_user) {
                        new Noty({
                            text: "La cantidad ingresada supera el stock disponible del producto.",
                            type: 'error'
                        }).show();
                    } else {
                        data = {
                            id: this.product.id,
                            product: this.product,
                            qty: Number(this.qty),
                        }
                        this.$emit('addProduct', data);
                        this.closeModal();
                    }
                } else {
                    new Noty({
                            text: "Ingresa una cantidad válida",
                            type: 'error'
                        }).show();
                }
            },

            /**
             * Cierra el collapse de los stocks
             */
            closeCollapseStocks() {
                this.collapseShow = false;
            },

            /**
             * Cierra el modal
             */
            closeModal() {
                this.product = null;
                $(this.$refs.modalProductStock).modal('hide');
            },

            /**
             * Abre el collapse de los stocks
             */
            openCollapseStocks() {
                this.collapseShow = true;
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
            qty: function(value) {
                var newQty = Number(value),
                    max_available = Number(this.product.stock_user);

                if (newQty < 0 || isNaN(newQty))  {
                    this.qty = 0;
                } else if (newQty > max_available) {
                    this.qty = max_available;
                }
            }
        }
    }
</script>

<style lang="scss">
</style>