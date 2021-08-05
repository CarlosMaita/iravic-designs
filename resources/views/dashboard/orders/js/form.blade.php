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

        let $customer_max_credit = 0;
        let datatable_products = $('#datatable_products');
        let datatable_products_resume = $('#datatable_products_resume');

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
        datatable_products_resume = datatable_products_resume.DataTable();

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
                updateOrderTotal();
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
            var container       = $('#customer-selected-container'),
                selected        = $('#customer').find(':selected'),
                address         = selected.data('address'),
                dni             = selected.data('dni'),
                maxcredit       = selected.data('max-credit'),
                maxcredit_str   = selected.data('max-credit-str'),
                name            = selected.data('name'),
                qualification   = selected.data('qualification'),
                telephone       = selected.data('telephone');

            container.find('#selected-customer-address').val(address);
            container.find('#selected-customer-dni').val(dni);
            container.find('#selected-customer-maxcredit').val(maxcredit_str);
            container.find('#selected-customer-name').val(name);
            container.find('#selected-customer-qualification').val(qualification);
            container.find('#selected-customer-telephone').val(telephone);
            container.removeClass('d-none');

            $customer_max_credit = maxcredit;
            $('.max-credit').text('$ ' + maxcredit_str);
        });

        /**
        *
        */
        function getOrderTotal() {
            var total = 0;

            $('.input-product-qty').not("tr.child .input-product-qty").each(function(index, item) {
                var price = Number($(item).data('price')),
                    val = Number(item.value);

                total += (price * val);
            });

            return total;
        }

        /**
        * 
        */
        function updateOrderTotal() {
            var total = getOrderTotal();
            $('.total').text(`$ ${total}`);
        }

        /**
        * 
        */
        function handleShowProductForm(product) {
            setProductModalHeaderInfo(product);
            addProductModalTable(product);
            modal_product.modal('show');
        }
        
        /**
        * 
        */
        function setProductModalHeaderInfo(product) {
            modal_product.find('.product-name').text(product.name);
            modal_product.find('.product-code').text(product.real_code);
            modal_product.find('.product-category').text(product.category.name);
            modal_product.find('.product-brand').text(product.brand.name);
        }
        
        /**
        * 
        */
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

        /**
        * 
        */
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

        /**
        * 
        */
        function getHtmlTableBodyProductStocks(product)
        {
            let html = '<tbody>';

            if (!product.product_id) {
                html = `<tr>
                            <th scope="row">${product.regular_price_str}</th>
                            <td>${product.stock_user}</td>
                            <td>
                                <div class="form-group">
                                    <input id="product-modal-input" class="form-control modal-product-input" type="number" min="0" step="any" data-id="${product.id}" data-stock="${product.stock_user}" value="1">
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
                                <input id="product-modal-input" class="form-control modal-product-input" type="number" min="0" step="any" data-id="${product.id}" data-stock="${product.stock_user}" value="1">
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
                `<input name="qtys[${product.id}]" class="form-control input-product-qty" type="number" min="0" max="${product.stock_user}" step="1" data-id="${product.id}" data-name="${product.name}" data-price="${product.regular_price}" data-stock="${product.stock_user}" value="${value}">`,
                `<input type="hidden" name="products[]" value="${product.id}">
                <button type="button" data-id="${product.id}" data-name="${product.name}" class="btn btn-sm btn-danger btn-action-icon remove-product" title="Eliminar" data-toggle="tooltip" style="width: auto;"><i class="fas fa-trash-alt"></i></button>`
            ]).draw(false);

            // Resume.. Confirm Step
            var row = datatable_products_resume.row.add( [
                product.name,
                product.real_code,
                product.gender,
                product.brand ? product.brand.name : '',
                product.category ? product.category.name : '',
                product.color ? product.color.name : '-',
                product.size ? product.size.name : '-',
                product.regular_price_str,
                value
            ])
            .draw(false)
            .node();

            $(row).addClass(`tr-product-${product.id}`);
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
        function updateDatatableResumeProductQty(product_id, new_qty)  {
            var tr = $(`.tr-product-${product_id}`);
            var data = datatable_products_resume.row(tr).data();
            data[8] = new_qty;
            datatable_products_resume.row(tr).data(data).draw();
        }

        // Add event listener for opening and closing details + Update child input with parent (Original input-product-qty) value
        datatable_products.on('click', 'tr', function () {
            var tr = $(this).closest('tr');
            var row = datatable_products.row(tr);
            var open = row.child.isShown();
            var input_qty = $(tr).find('.input-product-qty'),
                input_name = input_qty.attr('name');

            $('input[name="' + input_name + '"]').val(input_qty.val());
        });

        /**
        *
        */
        $('body').on('change', '.input-product-qty', function(e) {
            var stock = Number($(this).data('stock')),
                product_name = $(this).data('name'),
                product_id = $(this).data('id'),
                val = Number($(this).val()),
                qty_final = val,
                input_name = $(this).attr('name');

            if (val < 0 || isNaN(val))  {
                $(this).val(1);
                $('input[name="' + input_name + '"]').val(1);
                qty_final = 1;
            }

            if (val > stock) {
                qty_final = stock;
                $(this).val(stock);
                $('input[name="' + input_name + '"]').val(stock);

                new Noty({
                    text: `El límite en stock para el producto <b>${product_name}</b> es de ${stock}`,
                    type: 'error'
                }).show();
            }

            $('input[name="' + input_name + '"]').val(val);
            updateOrderTotal();
            updateDatatableResumeProductQty(product_id, qty_final);
        });

        /**
        *
        */
        $('body').on('click', 'tbody .remove-product', function (e) {
            var product_id = $(this).data('id'),
                name = $(this).data('name'),
                tr = $(this).parents('tr'),
                tr_modal = $(`.tr-product-${product_id}`);

            datatable_products.row(tr).remove().draw();
            datatable_products_resume.row(tr_modal).remove().draw();
            select_product.find('option[value="' + product_id + '"]').prop('disabled', false);
            select_product.select2();
            updateOrderTotal();

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
            var step = $(this).data('step');

            if (canGoNextStep(step)) {
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

        function canGoNextStep(step) {
            if (step == 1) {
                if (!$('#customer').val()) {
                    new Noty({
                        text: "Debe seleccionar un cliente.",
                        type: 'error'
                    }).show();
                    return false;
                }
            } else if (step == 2) {
                var products_with_qty = [];

                products_with_qty = $('.input-product-qty').filter(function(index, item) {
                    var val = Number(item.value);
                    return val > 0;
                });

                if (products_with_qty.length == 0) {
                    new Noty({
                        text: "Debe haber al menos 1 producto con cantidad válida.",
                        type: 'error'
                    }).show();
                    return false;
                }
            } else if (step == 3) {
                if (!$("input[name='payment_method']:checked", '#form-orders').val()) {
                    new Noty({
                        text: "Debe seleccionar un método de pago.",
                        type: 'error'
                    }).show();
                    return false;
                }
            }

            return true;
        }
    });
</script>