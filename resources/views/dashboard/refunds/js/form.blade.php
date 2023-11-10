<script>
    /**
     * No se usa
     * Funcionalidad se paso a VUE.JS 
     */
    $(function(){
        const FORM_RESOURCE_REFUNDS = $("#form-refunds");
        const URL_PRODUCTS = "{{ route('productos.index') }}";
        const URL_ORDER_DISCOUNT = "{{ route('ventas.discount') }}";
        const URL_REFUND_PRODUCTS = "{{ route('devoluciones.create') }}";
        const btn_add_product = $('#add-product');
        const btn_add_product_refund = $('#add-product-refund');
        const btn_add_product_modal = $('#add-product-modal');
        const btn_add_product_modal_refund = $('#add-product-modal-refund');
        const btn_apply_discount = $('#btn-apply-discount');
        const btn_open_modal_discount = $('#open-modal-discount');
        const modal_discount = $('#modal-discount');

        const modal_product = $('#modal-product');
        const modal_product_refund = $('#modal-product-refund');
        const modal_product_product_stocks = $('#product-add-stocks');
        const modal_product_product_refund_qty = $('#product-add-refund-qty');
        const select_customer = $('select#customer');
        const select_product = $('select#product');
        const select_product_refund = $('select#product_refund');

        let $customer_max_credit = 0;
        let datatable_products = $('#datatable_products');
        let datatable_products_refund = $('#datatable_products_refund');
        let datatable_products_resume = $('#datatable_products_resume');
        let datatable_products_resume_refund = $('#datatable_products_resume_refund');
        let discount_to_apply = 0;

        let resume_refund_products = $('#resume-products-refund');
            resume_order_products = $('#resume-products-order');

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
        datatable_products_refund = datatable_products_refund.DataTable();
        datatable_products_resume = datatable_products_resume.DataTable();
        datatable_products_resume_refund = datatable_products_resume_refund.DataTable();

        /**
         * Captura evento para cerrar collapse con stocks de un productos
         */
        $('body').on('click', '#btn-stocks-close', function(e) {
            var collapse = $('#stocks-collapse');
            collapse.collapse('hide');
        });

        /**
         *
         */
        FORM_RESOURCE_REFUNDS.on('submit', function (e) {
            e.preventDefault();
            if (modal_discount.hasClass('show')) return;

            $('#discount-input').val(discount_to_apply);

            var form = $('#form-refunds')[0];
            var formData = new FormData(form);
            
            $.ajax({
                    url: FORM_RESOURCE_REFUNDS.attr('action'),
                    type: FORM_RESOURCE_REFUNDS.attr('method'),
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
        });

        /**
         *
         */
        btn_open_modal_discount.on('click', function(e) {
            modal_discount.modal('show');
        });

        /**
         *
         */
        btn_apply_discount.on('click', function(e) {
            e.preventDefault();
            httpCalculateDiscount();
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
                balance         = selected.data('balance'),
                dni             = selected.data('dni'),
                maxcredit       = selected.data('max-credit'),
                maxcredit_str   = selected.data('max-credit-str'),
                name            = selected.data('name'),
                email            = selected.data('email'),
                qualification   = selected.data('qualification'),
                telephone       = selected.data('telephone');

            container.find('#selected-customer-address').val(address);
            container.find('#selected-customer-balance').val(balance);
            container.find('#selected-customer-dni').val(dni);
            container.find('#selected-customer-maxcredit').val(maxcredit_str);
            container.find('#selected-customer-name').val(name);
            container.find('#selected-customer-email').val(email);
            container.find('#selected-customer-qualification').val(qualification);
            container.find('#selected-customer-telephone').val(telephone);
            container.removeClass('d-none');

            $customer_max_credit = maxcredit;
            $('.max-credit').text(maxcredit_str);
            $('.customer-balance').text(balance);

            httpGetProductsForRefund(select_customer.val());
        });

        /**
         *
         */
        function getOrderTotal() {
            var subtotal = 0,
                total = 0;

            $('.input-product-qty').not("tr.child .input-product-qty").each(function(index, item) {
                var price = Number($(item).data('price')),
                    val = Number(item.value);

                subtotal += (price * val);
            });

            return {
                'subtotal': subtotal,
                'total': subtotal - discount_to_apply
            };

            return total;
        }

        /**
         * 
         */
        function updateOrderTotal() {
            var totals = getOrderTotal();

            $('.subtotal-order').text(`$ ${replaceNumberWithCommas(totals.subtotal)}`);
            $('.total-order').text(`$ ${replaceNumberWithCommas(totals.total)}`);
        }

        /**
         * 
         */
        function handleShowProductForm(product) {
            setProductModalHeaderInfo(product);
            addProductModalTable(product);
            addAllStocksToShow(product);
            modal_product.modal('show');

            $('#datatable_stocks').DataTable({
                responsive: true
            });
        }
        
        /**
         * 
         */
        function addAllStocksToShow(product) {
            @if (Auth::user()->isAdmin())
                var html = `<p class="text-right">
                                <button id="btn-stocks-collapse" class="btn btn-link" type="button" data-toggle="collapse" data-target="#stocks-collapse" aria-expanded="false" aria-controls="stocks-collapse">
                                    <i class="fa fa-eye" aria-hidden="true"></i> Ver todos los stocks
                                </button>
                            </p>
                            <div class="collapse" id="stocks-collapse">
                                <div class="card card-body">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table id="datatable_stocks" class="table" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" class="text-center">Depósito</th>
                                                                <th scope="col" class="text-center">Local</th>
                                                                <th scope="col" class="text-center">Camión</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center">${product.stock_depot}</td>
                                                                <td class="text-center">${product.stock_local}</td>
                                                                <td class="text-center">${product.stock_truck}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 text-right">
                                                <button id="btn-stocks-close" class="btn btn-link">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

                modal_product_product_stocks.append(html);
            @endif
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
        function getHtmlTableBodyProductStocks(product) {
            var html = '<tbody>';

            if (!product.product_id) {
                html = `<tr>
                            <th scope="row">${product.regular_price_str}</th>
                            <td>${product.stock_user}</td>
                            <td>
                                <div class="form-group">
                                    <input id="product-modal-input" class="form-control modal-product-input" type="number" min="0" step="1" data-id="${product.id}" data-stock="${product.stock_user}" value="1">
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
                                <input id="product-modal-input" class="form-control modal-product-input" type="number" min="0" step="1" data-id="${product.id}" data-stock="${product.stock_user}" value="1">
                            </div>
                        </td>
                    </tr>`;
            }
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

        /**
        *
        */
        function httpCalculateDiscount() {
            var data = FORM_RESOURCE_ORDERS.serialize();
            $.ajax({
                url: `${URL_ORDER_DISCOUNT}`,
                type: "GET",
                data: data,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.success) {
                        modal_discount.modal('hide');
                        discount_to_apply = res.data.discount;
                        $('.discount').text(res.data.discount_format);
                        $('.subtotal').text(res.data.subtotal);
                        $('.total').text(res.data.total);
                    } else {
                        discount_to_apply = 0;
                        updateOrderTotal();

                        new Noty({
                            text: "No se ha podido aplicar el descuento al total de la compra.",
                            type: 'error'
                        }).show();
                    }
                },
                error: function(e) {
                    discount_to_apply = 0;
                    updateOrderTotal();

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
                            text: "No se ha podido aplicar el descuento al total de la compra.",
                            type: 'error'
                        }).show();
                    }
                }
            });
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

        // Utilities
        function replaceNumberWithCommas(number) {
            //Seperates the components of the number
            var n= number.toString().split(".");
            //Comma-fies the first part
            n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            //Combines the two sections
            return n.join(",");
        }










        /*************************** Refunds ****************************/

        /**
        *
        */
        btn_add_product_refund.on('click', function(e) {
            var selected = select_product_refund.val();

            if (product = getProductRefundSelected(selected)) {
                setProductModalHeaderInfoRefund(product);
                addProductModalTableRefund(product.order_products);
                modal_product_refund.modal('show');
            }
        });


        /**
        *
        */
        btn_add_product_modal_refund.on('click', function(e) {
            var inputs = $('.modal-product-refund-input'),
                error = false,
                products_to_refund = [],
                select_product_id = select_product_refund.val();

            inputs.each((index, input) => {
                var max = $(input).attr('max'),
                    product_id = $(input).data('id'),
                    value = input.value;

                if (value > 0 && value <= max) {
                    var obj = {
                        'product_id': product_id,
                        'qty': value
                    }

                    products_to_refund.push(obj);
                } else if (value > max) {
                    error = true;

                    new Noty({
                        text: "La cantidad a devolver para el producto " + (index + 1) + " es mayor a la que puede devolver.",
                        type: 'error'
                    }).show();
                }
            });

            if (products_to_refund.length) {
                addProductsToDatatableRefund(select_product_id, products_to_refund);
                updateRefundTotal();
            } else if (!error) {
                new Noty({
                    text: "Debe agregar cantidades a retornar.",
                    type: 'error'
                }).show();
            }
        });


        /**
        *
        */
        modal_product_refund.on('hidden.coreui.modal', function(e) {
            modal_product_product_refund_qty.empty();
        });
        
        /**
        *
        */
        $('body').on('change', '.input-product-refund-qty', function(e) {
            var available = Number($(this).attr('max')),
                product_name = $(this).data('name'),
                product_id = $(this).data('id'),
                price = $(this).data('price'),
                val = Number($(this).val()),
                qty_final = val,
                input_name = $(this).attr('name');
            
            if (val < 0 || isNaN(val))  {
                qty_final = 1;
                $(this).val(1);
                $('input[name="' + input_name + '"]').val(1);
            }

            if (val > available) {
                qty_final = available;
                $(this).val(available);
                $('input[name="' + input_name + '"]').val(available);
            }

            updateRefundTotal();
            updateDatatableResumeRefundProductQty(product_id, qty_final)
        });


        /**
        *
        */
        $('body').on('click', 'tbody .remove-product-refund', function (e) {
            var product_id = $(this).data('id'),
                name = $(this).data('name'),
                tr = $(this).parents('tr'),
                tr_modal_resume = $(`.tr-product-refund-${product_id}`);

            datatable_products_refund.row(tr).remove().draw();
            datatable_products_resume.row(tr_modal_resume).remove().draw();
            updateRefundTotal();

            new Noty({
                    text: `Producto <b>${name}</b> eliminado con éxito.`,
                    type: 'success'
                }).show();
        });

        /**
        *
        */
        function httpGetProductsForRefund(customer_id) {
            var url = `${URL_REFUND_PRODUCTS}?cliente=${customer_id}`;
            select_product_refund.empty();

            $.get(url, function(res) {
                $productsForRefund = res;
                select_product_refund.append('<option selected disabled>Seleccionar</option>');

                $productsForRefund.forEach(product => {
                    var html = `<option value="${product.id}" 
                                    data-id="${product.id}"
                                    data-brand="${product.brand_name}"
                                    data-category="${product.category_name}"
                                    data-code="${product.code}"
                                >
                                    ${product.name} - Cod:${product.code} 
                                </option>`;

                    select_product_refund.append(html);
                });

                select_product_refund.select2();
            })
            .fail(function() {
                $productsForRefund = [];

                new Noty({
                    text: "No se ha podido obtener los productos disponibles para devolución.",
                    type: 'error'
                }).show();
            });
        }

        /**
        * 
        */
        function setProductModalHeaderInfoRefund(product) {
            modal_product_refund.find('.product-name').text(product.name);
            modal_product_refund.find('.product-code').text(product.code);
            modal_product_refund.find('.product-category').text(product.category_name);
            modal_product_refund.find('.product-brand').text(product.brand_name);
        }
        
        /**
        * 
        */
        function addProductModalTableRefund(products) {
            var html,
                table_header = getHtmlTableHeaderProductStocksRefund(),
                table_body = getHtmlTableBodyProductsRefundQtys(products);

            html = `<div class="row">
                        <div class="table-responsive">
                            <table class="table">
                                ${table_header}
                                ${table_body}
                            </table>
                        </div>
                    </div>`;

            modal_product_product_refund_qty.append(html);
        }
        
        /**
        * 
        */
        function getHtmlTableHeaderProductStocksRefund() {
            return `<thead>
                            <tr>
                                <th scope="col" style="width: 20%;">ID venta</th>
                                <th scope="col" style="width: 20%;">Color</th>
                                <th scope="col" style="width: 20%;">Talla</th>
                                <th scope="col" style="width: 20%;">Puede Devolver</th>
                                <th scope="col" style="width: 20%;">Devolver</th>
                            </tr>
                        </thead>`;
        }

        /**
        * 
        */
        function getHtmlTableBodyProductsRefundQtys(products) {
            var html = '<tbody>';
            
            products.forEach(product => {
                html +=     `<tr>
                                <th scope="row">${product.order_id}</th>
                                <td>${product.color?.name}</td>
                                <td>${product.size?.name}</td>
                                <td>${product.available_for_refund}</td>
                                <td>
                                    <div class="form-group">
                                        <input class="form-control modal-product-refund-input" type="number" min="0" max="${product.available_for_refund}" step="1" data-id="${product.id}" value="">
                                    </div>
                                </td>
                            </tr>`;
            });

            html += '</tbody>';

            return html;
        }

        /**
        *
        */
        function appendProductToProductsDatatableRefund(product, value) {
            if ($(`#input-product-refund-${product.id}`).length) {
                $(`#input-product-refund-${product.id}`).val(value);
            } else {
                datatable_products_refund.row.add([
                    product.order_id,
                    product.product_name,
                    product.product.real_code,
                    product.product.gender,
                    product.product.brand ? product.product.brand.name : '',
                    product.product.category ? product.product.category.name : '',
                    product.color ? product.color.name : '-',
                    product.size ? product.size.name : '-',
                    product.product_price_str,
                    product.is_by_credit ? 'Si' : 'No',
                    product.available_for_refund,
                    // product.stock_user,
                    `<input name="qtys_refund[${product.id}]" 
                            class="form-control input-product-refund-qty" 
                            type="number" 
                            min="0" 
                            max="${product.available_for_refund}" 
                            step="1" 
                            data-id="${product.id}" 
                            data-name="${product.product_name}" 
                            data-price="${product.product_price}" 
                            data-is-by-credit="${product.is_by_credit}" 
                            value="${value}">`,

                    `<input id="input-product-refund-${product.id}" 
                            type="hidden" 
                            name="products_refund[]" 
                            value="${product.id}">

                    <button type="button" data-id="${product.id}" data-name="${product.product_name}" class="btn btn-sm btn-danger btn-action-icon remove-product-refund" title="Eliminar" data-toggle="tooltip" style="width: auto;"><i class="fas fa-trash-alt"></i></button>`
                ]).draw(false);
            }

            var row = datatable_products_resume_refund.row.add([
                product.order_id,
                product.product_name,
                product.product.real_code,
                product.product.gender,
                product.product.brand ? product.product.brand.name : '',
                product.product.category ? product.product.category.name : '',
                product.color ? product.color.name : '-',
                product.size ? product.size.name : '-',
                product.product_price_str,
                value
            ])
            .draw(false)
            .node();

            $(row).addClass(`tr-product-refund-${product.id}`);

            $('#datatable_products_refund').DataTable()
                                    .columns.adjust()
                                    .responsive.recalc();
        }
        
        /**
        *
        */
        function addProductsToDatatableRefund(select_product_id, products) {
            products.forEach(element => {
                var product = getProductFromArrayRefund(select_product_id, element.product_id);

                if (product) {
                    appendProductToProductsDatatableRefund(product, element.qty);
                    modal_product_refund.modal('hide');
                }
            });

            updateRefundTotal();
        }


        /**
        *
        */
        function updateDatatableResumeRefundProductQty(product_id, new_qty)  {
            var tr = $(`.tr-product-refund-${product_id}`);
            var data = datatable_products_resume_refund.row(tr).data();
            data[8] = new_qty;
            datatable_products_resume_refund.row(tr).data(data).draw();
        }

        /**
        * 
        */
        function getProductFromArrayRefund(select_product_id, product_id) {
            var order_product = null,
                select_product = $productsForRefund.find(obj => {
                    return obj.id == select_product_id
                });

            if (select_product) {
                order_product = select_product.order_products.find(obj => {
                    return obj.id == product_id
                });
            }

            return order_product;
        }

        /**
         *
         *
         */
         function getProductRefundSelected(product_id) {
            return $productsForRefund.find(obj => {
                return obj.id == product_id
            });
        }

        /**
        * 
        */
        function updateRefundTotal() {
            var totals = getRefundTotals();
            $('.total-refund-credit').text(`$ ${replaceNumberWithCommas(totals.total_credit)}`);
            $('.total-refund-others').text(`$ ${replaceNumberWithCommas(totals.total_others)}`);
            $('.total-refund').text(`$ ${replaceNumberWithCommas(totals.total)}`);
        }

        function getRefundTotals() {
            var total_credit = 0,
                total_others = 0,
                total = 0;

            $('.input-product-refund-qty').not("tr.child .input-product-refund-qty").each(function(index, item) {
                var price = Number($(item).data('price')),
                    val = Number(item.value),
                    is_by_credit = $(item).data('is-by-credit'),
                    total_product = (price * val);

                if (is_by_credit) {
                    total_credit += total_product;
                } else {
                    total_others += total_product;
                }
            });

            return {
                total_credit: total_credit,
                total_others: total_others,
                total: total_credit + total_others
            }
        }
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

                if (step == 1) {
                    $('#datatable_products_refund').DataTable()
                                    .columns.adjust()
                                    .responsive.recalc();
                } else if (step == 2) {
                    $('#datatable_products').DataTable()
                                    .columns.adjust()
                                    .responsive.recalc();
                } else if (step == 3 && $('.input-product-qty').length == 0) {
                    $('#card-payment').addClass('d-none');
                    resume_order_products.addClass('d-none');
                } else if (step == 3) {
                    $('#card-payment').removeClass('d-none');
                    resume_order_products.removeClass('d-none');
                } if (step == 4) {
                    $('#datatable_products_resume_refund').DataTable()
                                    .columns.adjust()
                                    .responsive.recalc();
                }
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

                products_with_qty = $('.input-product-refund-qty').filter(function(index, item) {
                    var val = Number(item.value);
                    return val > 0;
                });

                if (products_with_qty.length == 0) {
                    new Noty({
                        text: "Debe seleccionar al menos 1 producto a devolver.",
                        type: 'error'
                    }).show();
                    return false;
                }
            } else if (step == 4 && $('.input-product-qty').length > 0) {
                if (!$("input[name='payment_method']:checked", '#form-refunds').val()) {
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