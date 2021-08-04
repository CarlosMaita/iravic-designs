<script>
    $(function(){
        const FORM_RESOURCE_ORDERS = $("#form-orders");
        const URL_PRODUCTS = "{{ route('productos.index') }}";
        const btn_add_customer = $('#add-customer');
        const btn_add_product = $('#add-product');
        const btn_add_product_modal = $('#add-product-modal');
        const btn_cancel_new_customer = $('#btn-cancel-new-customer');
        const container_new_customer = $('#container-new-customer');
        const modal_new_customer = $('#modal-new-customer');
        const modal_product = $('#modal-product');
        const modal_product_product_stocks = $('#product-add-stocks');
        const select_customer = $('select#customer');
        const select_product = $('select#product');

        let id_product_modal = null;
        let prdouct_modal = null;
        let datatable_products = $('#datatable_products');
        let order_products = [];

        $('select').select2({
            matcher: function(params, data) {
                if ($.trim(params.term) === '') {
                    return data;
                }

                // Check if the text contains the term
                // if (original.indexOf(term) > -1) { return data; }

                if (
                    $(data.element).data('brand') && $(data.element).data('category') && $(data.element).data('code') &&
                    (
                        $(data.element).data('brand').toString().indexOf(params.term) > -1 ||
                        $(data.element).data('category').toString().indexOf(params.term) > -1 ||
                        $(data.element).data('code').toString().indexOf(params.term) > -1
                    )
                ) {
                    return data;
                }

                return null;
            }
        });

        $('[data-toggle="tooltip"]').tooltip();

        datatable_products = datatable_products.DataTable();

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

            if (selected) {
                $.ajax({
                    url: `${URL_PRODUCTS}/${selected}`,
                    type: "GET",
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res) {
                            prdouct_modal = res;
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
        });

        /**
        *
        */
        btn_add_product_modal.on('click', function(e) {
            var input = $('#product-modal-input'),
                product_id = input.data('id'),
                stock = Number(input.data('stock')),
                value = Number(input.val());

            if (isProductValid(stock, value)) {
                addProductToDatatable(product_id, value);
            }

            // $('.modal-product-input').each(function(index, item) {
            //     var elem = $(item),
            //         id = elem.data('id'),
            //         type = elem.data('type'),
            //         value = elem.val();
            // });
        });

        /**
        *
        */
        function addProductToDatatable(product_id, value) {
            var product = getProductFromArray(product_id);

            if (product) {
                appendProductToProductsDatatable(product, value);
                select_product.find('option[value="' + product_id + '"]').prop('disabled', true);
                select_product.select2();
                select_product.val('Seleccionar').trigger('change');
                modal_product.modal('hide');
            }
        }

        /**
        *
        */
        function appendProductToProductsDatatable(product, value) {
            datatable_products.row.add( [
                product.name,
                product.real_code,
                product.gender,
                product.brand ? product.brand.name : '',
                product.category ? product.category.name : '',
                product.color ? product.color.name : '-',
                product.size ? product.size.name : '-',
                product.regular_price_str,
                product.stock_user,
                `<input class="form-control input-product-qty" type="number" min="0" max="${product.stock_user}" step="1" data-name="${product.name}" data-stock="${product.stock_user}" value="${value}">`,
                `<button type="button" data-id="${product.id}" data-name="${product.name}" class="btn btn-sm btn-danger btn-action-icon remove-product" title="Eliminar" data-toggle="tooltip" style="width: auto;"><i class="fas fa-trash-alt"></i></button>`
            ]).draw(false);
        }

        /**
        *
        */
        function getProductFromArray(product_id) {
            return $products.find(obj => {
                return obj.id === product_id
            });
        }

        /**
        *
        */
        function isProductValid(stock, value) {
            var valid = true;

            if (value && !isNaN(value)) {
                if (value > stock) {
                    valid = false;
                    new Noty({
                        text: "La cantidad ingresada sobrepasa el stock disponible del producto.",
                        type: 'error'
                    }).show();
                }
            } else {
                valid = false;
                new Noty({
                        text: "Ingresa una cantidad válida",
                        type: 'error'
                    }).show();
            }

            return valid;
        }
        

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
        modal_product.on('hidden.coreui.modal', function(e) {
            modal_product_product_stocks.empty();
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
            setProductModalHeaderInfo(product);
            addProductModalTable(product);
            modal_product.modal('show');
        }

        function setProductModalHeaderInfo(product) {
            modal_product.find('.product-name').text(product.name);
            modal_product.find('.product-code').text(product.real_code);
            modal_product.find('.product-category').text(product.category.name);
            modal_product.find('.product-brand').text(product.brand.name);
        }
        
        function addProductModalTable(product) {
            var html,
                table_header = getHtmlTableHeaderProductStocks(product.is_regular),
                table_body = getHtmlTableBodyProductStocks(product);

            html = `<div class="row">
                        <div class="table-responsive">
                            <table class="table">
                                ${table_header}
                                ${table_body}
                            </table>
                        </div>
                    </div>`;

            modal_product_product_stocks.append(html);
        }

        function getHtmlTableHeaderProductStocks(is_regular) {
            var html = '';

            if (is_regular) {
                html = `<thead>
                            <tr>
                                <th scope="col" style="width: 33%;">Precio</th>
                                <th scope="col" style="width: 33%;">Stock</th>
                                <th scope="col" style="width: 33%;">Cantidad</th>
                            </tr>
                        </thead>`;
            } else {
                html = `<thead>
                            <tr>
                                <th scope="col" style="width: 20%;">Color</th>
                                <th scope="col" style="width: 20%;">Talla</th>
                                <th scope="col" style="width: 20%;">Precio</th>
                                <th scope="col" style="width: 20%;">Stock</th>
                                <th scope="col" style="width: 20%;">Cantidad</th>
                            </tr>
                        </thead>`;
            }

            return html;
        }

        function getHtmlTableBodyProductStocks(product)
        {
            let html = '<tbody>';

            if (!product.product_id) {
                html = `<tr>
                            <th scope="row">${product.regular_price_str}</th>
                            <td>${product.stock_user}</td>
                            <td>
                                <div class="form-group">
                                    <input id="product-modal-input" class="form-control modal-product-input" type="number" min="0" step="any" data-id="${product.id}" data-stock="${product.stock_user}" data-type="regular">
                                </div>
                            </td>
                        </tr>`;
            } else {
                 html += `<tr>
                        <th scope="row">${product.color?.name}</th>
                        <td>${product.size?.name}</td>
                        <td>${product.regular_price_str}</td>
                        <td>${product.stock_user}</td>
                        <td>
                            <div class="form-group">
                                <input id="product-modal-input" class="form-control modal-product-input" type="number" min="0" step="any" data-id="${product.id}" data-stock="${product.stock_user}" data-type="combination">
                            </div>
                        </td>
                    </tr>`;
            }

            // if (product.is_regular) {
            //     html = `<tr>
            //                 <th scope="row">${product.regular_price_str}</th>
            //                 <td>${product.stock_user}</td>
            //                 <td>
            //                     <div class="form-group">
            //                         <input class="form-control modal-product-input" type="number" min="0" step="any" data-id="${item.id}" data-type="regular">
            //                     </div>
            //                 </td>
            //             </tr>`;
            // } else {
            //     product.product_combinations.forEach(function(item) {
            //         html += `<tr>
            //                     <th scope="row" data-toggle="tooltip" data-placement="top" title="Código: ${product.real_code}">${item.color?.name}</th>
            //                     <td>${item.size?.name}</td>
            //                     <td>${item.regular_price_str}</td>
            //                     <td>${item.stock_user}</td>
            //                     <td>
            //                         <div class="form-group">
            //                             <input class="form-control modal-product-input" type="number" min="0" step="any" data-id="${item.id}" data-type="combination">
            //                         </div>
            //                     </td>
            //                 </tr>`;
            //     });
            // }

            html += '</tbody>';

            return html;
        }

        /**
        *
        */
        $('body').on('change', '.input-product-qty', function(e) {
            var stock = Number($(this).data('stock')),
                name = $(this).data('name'),
                val = Number($(this).val());

            if (val < 0 || isNaN(val))  {
                $(this).val(1);
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
            var product_id = $(this).data('id'),
                name = $(this).data('name'),
                tr = $(this).parents('tr');

            datatable_products.row(tr).remove().draw();
            select_product.find('option[value="' + product_id + '"]').prop('disabled', false);
            select_product.select2();

            new Noty({
                    text: `Producto <b>${name}</b> eliminado con éxito.`,
                    type: 'success'
                }).show();
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