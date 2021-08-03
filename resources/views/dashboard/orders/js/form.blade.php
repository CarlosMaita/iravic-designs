<script>
    $(function(){
        const FORM_RESOURCE_ORDERS = $("#form-orders");
        const URL_PRODUCTS = "{{ route('productos.index') }}";
        const btn_add_customer = $('#add-customer');
        const btn_add_product = $('#add-product');
        const btn_cancel_new_customer = $('#btn-cancel-new-customer');
        const container_new_customer = $('#container-new-customer');
        const modal_new_customer = $('#modal-new-customer');
        const modal_product = $('#modal-product');
        const select_customer = $('select#customer');
        const select_product = $('select#product');

        let datatable_products = $('#datatable_products');
        let order_products = [];

        $('select').select2();

        datatable_products.DataTable();


        /**
        *
        */
        FORM_RESOURCE_ORDERS.on('submit', function (e) {
            e.preventDefault();
            var form = $('#form-orders')[0];
            var formData = new FormData(form);
            
            $.ajax({
                    url: FORM_RESOURCE_ORDERS.attr('action'),
                    type: FORM_RESOURCE_ORDERS.attr('method'),
                    enctype: 'multipart/form-data',
                    data: formData,
                    processData: false,
                    contentType: false,
                success: function (response) {
                    if (response.success) {
                        window.location.href = response.data.redirect;
                    } else if (response.message){
                        new Noty({
                            text: response.message,
                            type: 'error'
                        }).show();
                    } else if (response.error) {
                        new Noty({
                            text: response.error,
                            type: 'error'
                        }).show();
                    } else {
                        new Noty({
                            text: "{{ __('dashboard.general.operation_error') }}",
                            type: 'error'
                        }).show();
                    }
                },
                error: function (e) {
                    if (e.responseJSON.errors) {
                        $.each(e.responseJSON.errors, function (index, element) {
                            if ($.isArray(element)) {
                                new Noty({
                                    text: element[0],
                                    type: 'error'
                                }).show();
                            }
                        });
                    } else if (e.responseJSON.error){
                        new Noty({
                            text: e.responseJSON.error,
                            type: 'error'
                        }).show();
                    } else if (e.responseJSON.message){
                        new Noty({
                            text: e.responseJSON.message,
                            type: 'error'
                        }).show();
                    } else {
                        new Noty({
                            text: "{{ __('dashboard.general.operation_error') }}",
                            type: 'error'
                        }).show();
                    }
                }
            });
        });

        /**
        *
        */
        btn_add_product.on('click', function(e) {
            var selected = select_product.val();

            console.log(selected);

            if (selected) {
                $.ajax({
                    url: `${URL_PRODUCTS}/${selected}`,
                    type: "GET",
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res) {
                            handleShowProductForm(res);
                        } else {
                            new Noty({
                                text: "No se ha podido obtener la información del producto en este momento.",
                                type: 'error'
                            }).show();
                        }
                    },
                    error: function(e) {
                        if (e.responseJSON.message) {
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
                                text: "No se ha podido obtener la información del producto en este momento.",
                                type: 'error'
                            }).show();
                        }
                    }
                });
            }


            /**
            *
            *   
            *
            */
        });

        /*
            - desactivar en el selector al agregar producto
        */

        /**
        *
        */
        btn_add_customer.on('click', function(e) {
            e.preventDefault();
            modal_new_customer.modal('show');
            select_customer.attr('disabled', true);
        });

        /**
        *
        */
        btn_cancel_new_customer.on('click', function(e) {
            e.preventDefault();
            modal_new_customer.modal('hide');
            select_customer.attr('disabled', false);
        });

        /**
        *
        */
        modal_new_customer.on('hidden.coreui.modal', function(e) {
            select_customer.attr('disabled', false);
        });

        /**
        *
        */
        select_customer.on('change', function(e) {
            var container = $('#customer-selected-container'),
                selected = $('#customer').find(':selected'),
                address = selected.data('address'),
                dni = selected.data('dni'),
                name = selected.data('name'),
                qualification = selected.data('qualification'),
                telephone = selected.data('telephone');

            container.find('#selected-customer-address').val(address);
            container.find('#selected-customer-dni').val(dni);
            container.find('#selected-customer-name').val(name);
            container.find('#selected-customer-qualification').val(qualification);
            container.find('#selected-customer-telephone').val(telephone);
            container.removeClass('d-none');
        });


        /**
        * 
        */
        function handleShowProductForm(product) {
            console.log(product);
            modal_product.modal('show');
        }

        /**
        *
        */
        $('body').on('change', '.input-product-qty', function(e) {
            var stock = Number($(this).data('stock')),
                name = $(this).data('name'),
                val = Number($(this).val());

            if (val < 0 || isNaN(val))  {
                $(this).val(0);
            }

            if (val > stock) {
                $(this).val(stock);

                new Noty({
                    text: `El límite en stock para el producto <b>${name}</b> es de ${stock}`,
                    type: 'error'
                }).show();
            }
        });

        /**
        *
        */
        $('body').on('click', 'tbody .remove-product', function (e) {
            var id = $(this).data('id'),
                name = $(this).data('name'),
                tr = $(this).parents('tr');

            datatable_products.DataTable().row(tr).remove().draw();

            new Noty({
                    text: `Producto <b>${name}</b> eliminado con éxito.`,
                    type: 'success'
                }).show();

            /*
                - activar en el selector
            */
        });
    });

    /**
    * Steps form
    */
    $(document).ready(function(){
        var current_fs, next_fs, previous_fs; //fieldsets
        var opacity;

        $(".next").click(function() {
            if (canGoNextStep(this)) {
                current_fs = $(this).parent();
                next_fs = $(this).parent().next();

                //Add Class Active
                $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                //show the next fieldset
                next_fs.show();
                //hide the current fieldset with style
                current_fs.animate({opacity: 0}, {
                    step: function(now) {
                        // for making fielset appear animation
                        opacity = 1 - now;

                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                        next_fs.css({'opacity': opacity});
                    },
                    duration: 600
                });
            }
        });

        $(".previous").click(function() {
            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();

            //Remove class active
            $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

            //show the previous fieldset
            previous_fs.show();

            //hide the current fieldset with style
            current_fs.animate({opacity: 0}, {
                step: function(now) {
                    // for making fielset appear animation
                    opacity = 1 - now;

                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    previous_fs.css({'opacity': opacity});
                },
                duration: 600
            });
        });

        $('.radio-group .radio').click(function(){
            $(this).parent().find('.radio').removeClass('selected');
            $(this).parent().find('input').prop("checked", false);
            $(this).addClass('selected');
            $(this).find('input').prop("checked", true);
        });

        function canGoNextStep(element) {
            var step = $(element).data('step');
            
            if (step == 1) {
                // if (!$('#customer').val()) {
                //     new Noty({
                //         text: "Debe seleccionar un cliente.",
                //         type: 'error'
                //     }).show();
                //     return false;
                // }
            } else if (step == 2) {

            } else if (step == 3) {

            }

            return true;
        }
    });
</script>