<template>
    <div id="modal-discount" ref="modalDiscount" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Aplicar descuento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label>Total actual</label>
                            <p class="total font-weight-bold">{{ total | toCurrency }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group text-left">
                                <label for="discount-input">Descuento</label>
                                <input v-model="discount" id="discount-input" class="form-control" name="discount"  type="number" min="0" step="any" autocomplete="nope">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-left">
                                <label for="discount_password">Contraseña para descuentos</label>
                                <input v-model="password" id="discount_password" class="form-control" name="discount_password" type="password" autocomplete="nope">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button @click="validate" class="btn btn-primary" type="button">Aplicar</button>
                    <button class="btn btn-danger" data-dismiss="modal" type="button">Cancelar</button>
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
            urlDiscount: {
                type: String,
                default: ''
            }
        },
        data: () => ({
            discount: 0,
            password: null,
            total: 0
        }),
	    computed: {
	    },
        async mounted() {
        },
        methods: {
            /**
             * Aplica descuento al componente padre emitiendo evento 'addDiscount;
             * EL descuento se vera reflejado en el componente padre
             */
            addDiscount() {
                this.$emit('addDiscount', Number(this.discount));
                this.closeModal();
            },

            /**
             * Cierra modal
             */
            closeModal() {
                $(this.$refs.modalDiscount).modal('hide');
            },

            /**
             * Abre modal
             */
            showModal(total, discount) {
                this.discount = discount;
                this.total = total;
                $(this.$refs.modalDiscount).modal('show');
            },

            /**
             * Peticion HTTP para confirmar que contrasena ingresada para aplicar descuentos es correcta con la que se encuentra en configuracion
             */
            validate() {
                var self = this;

                $.get(this.urlDiscount, { discount_password: self.password }, function(res) {
                    if (res.success) {
                        self.addDiscount();
                    } else {
                        new Noty({
                            text: "La verificación de la contraseña ha fallado",
                            type: 'error'
                        }).show();
                    }
                })
                .fail(function(e) {
                    if (e.responseJSON.errors) {
                        $.each(e.responseJSON.errors, function (index, element) {
                            if ($.isArray(element)) {
                                new Noty({
                                    text: element[0],
                                    type: 'error'
                                }).show();
                            }
                        });
                    } else if (e.responseJSON.message) {
                        new Noty({
                            text: e.responseJSON.message,
                            type: 'error'
                        }).show();
                    } else if (e.responseJSON.error) {
                        new Noty({
                            text: e.responseJSON.error,
                            type: 'error'
                        }).show();
                    } else {
                        new Noty({
                            text: "La verificación de la contraseña ha fallado",
                            type: 'error'
                        }).show();
                    }
                });
            }
        },
        watch: {
            /**
             * Evento de cambio de descuento.
             * Si el campo ingresado no es numerico, lo setea en 0
             */
            discount: function (newVal) {
                if (isNaN(newVal)) {
                    this.discount = 0;
                }
            }
        }
    }
</script>

<style lang="scss">
</style>