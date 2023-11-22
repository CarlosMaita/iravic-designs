<script>
    let $visit_container = $('#new-visit-container');
    let $customer_balance = 0;
    let $customer_max_credit = 0;

    $(function(){
        const FORM_RESOURCE_ORDERS = $("#form-orders");
        const URL_PRODUCTS = "{{ route('productos.index') }}";
        const URL_ORDER_DISCOUNT = "{{ route('ventas.discount') }}";
        const btn_add_customer = $('#add-customer');
        const btn_add_product = $('#add-product');
        const btn_add_product_modal = $('#add-product-modal');
        const btn_apply_discount = $('#btn-apply-discount');
        const btn_open_modal_discount = $('#open-modal-discount');
        const btn_cancel_new_customer = $('#btn-cancel-new-customer');
        const container_new_customer = $('#container-new-customer');
        const modal_discount = $('#modal-discount');
        const modal_new_customer = $('#modal-new-customer');
        const modal_product = $('#modal-product');
        const modal_product_product_stocks = $('#product-add-stocks');
        const select_customer = $('select#customer');
        const select_product = $('select#product');

        let datatable_products = $('#datatable_products');
        let datatable_products_resume = $('#datatable_products_resume');
        let discount_to_apply = 0;

        setDatePicker();
        select_customer.select2({
            matcher: matchCustomer
        });
        select_product.select2({
            matcher: matchProduct
        });

        $('[data-toggle="tooltip"]').tooltip();

        datatable_products = datatable_products.DataTable();
        datatable_products_resume = datatable_products_resume.DataTable();

        /**
         * Captura evento de click para cerrar collapse de stocks de un producto
         */
        $('body').on('click', '#btn-stocks-close', function(e) {
            var collapse = $('#stocks-collapse');
            collapse.collapse('hide');
        });

        /**
         * Captura evento de submit de formulario productos
         */
        FORM_RESOURCE_ORDERS.on('submit', function (e) {
            e.preventDefault();
            if (modal_discount.hasClass('show')) return;

            $('#discount-input').val(discount_to_apply);

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
         * Captura evento para agregar producto a la venta
         * Realiza peticion HTTP para obtener los stocks del producto
         * Si tiene stock asociado al rol del usuario logueado, le abre el formulario
         * Sino, le muestra un sweetalert indicando los stocks totales
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
                        var product = res;
                        console.log(res);
                        if (product && product.stock_user > 0) {
                            handleShowProductForm(product);
                        } else if (product) {
                            swal({
                                title: 'Sin stock asociado',
                                html: `<div class="d-inline-flex flex-column text-left">
                                            <p class="mb-0">Depósito: ${product.stock_depot}</p>
                                            <p class="mb-0">Local: ${product.stock_local}</p>
                                            <p class="mb-0">Camión: ${product.stock_truck}</p>
                                        </div>`,
                                type: 'info',
                            }).then(function () {
                            }).catch(swal.noop);
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
         * Captura evento de abrir modal para seleccionar un producto y ser agregado a la venta
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
         * Captura evento para crear un nuevo cliente desde la venta
         */
        btn_add_customer.on('click', function(e) {
            e.preventDefault();
            modal_new_customer.modal('show');
            select_customer.attr('disabled', true);
        });

        /**
         * Captura evento para cancelar creacion de nuevo cliente
         */
        btn_cancel_new_customer.on('click', function(e) {
            e.preventDefault();
            modal_new_customer.modal('hide');
            select_customer.attr('disabled', false);
        });

        /**
         * Captura evento para abrir modal de descuento
         */
        btn_open_modal_discount.on('click', function(e) {
            modal_discount.modal('show');
        });

        /**
         * Captura evento para aplicar descuento
         * Luego llama a funcion para realizar peticion HTTP y validar contrasena para descuentos
         */
        btn_apply_discount.on('click', function(e) {
            e.preventDefault();
            httpCalculateDiscount();
        });

        /**
         * Captura evento de cerrar modal para crear nuevo cliente desde la venta
         */
        modal_new_customer.on('hidden.coreui.modal', function(e) {
            select_customer.attr('disabled', false);
        });

        /**
         * Captura evento para cerrar modal de agregar producto a la venta
         */
        modal_product.on('hidden.coreui.modal', function(e) {
            modal_product_product_stocks.empty();
        });

        /**
         * Captura evento de cambio de cliente seleccionado
         */
        select_customer.on('change', function(e) {
            var container       = $('#customer-selected-container'),
                selected        = $('#customer').find(':selected'),
                address         = selected.data('address'),
                balance         = selected.data('balance'),
                balance_numeric = selected.data('balance-numeric'),
                dni             = selected.data('dni'),
                maxcredit       = selected.data('max-credit'),
                maxcredit_str   = selected.data('max-credit-str'),
                name            = selected.data('name'),
                email           = selected.data('email'),
                qualification   = selected.data('qualification'),
                telephone       = selected.data('telephone');

            container.find('#selected-customer-address').text(address);
            container.find('#selected-customer-balance').text(balance);
            container.find('#selected-customer-dni').text(dni);
            container.find('#selected-customer-maxcredit').text(maxcredit_str);
            container.find('#selected-customer-name').text(name);
            container.find('#selected-customer-email').text(email);
            container.find('#selected-customer-qualification').text(qualification);
            container.find('#selected-customer-telephone').text(telephone);
            container.removeClass('d-none');
            
            $customer_balance = balance_numeric;
            $customer_max_credit = maxcredit;
            $('.max-credit').text(maxcredit_str);
            $('.customer-balance').text(balance);
        });

        /**
         * Retorna el total de la venta
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
         * Actualiza los textos de subtotal y total de la venta
         * Los totales son obtenidos de la funcion getOrderTotal
         */
        function updateOrderTotal() {
            var totals = getOrderTotal();
            $('.subtotal').text(`$ ${replaceNumberWithCommas(totals.subtotal)}`);
            $('.total').text(`$ ${replaceNumberWithCommas(totals.total)}`);
        }

        /**
         * Procesa mostrar el formulario de un producto. Se llaman funciones para:
         * - Llenar el header del modal con los datos base del producto
         * - Llenar la tabla del producto
         * - Se muestran los stocks del producto
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
         * Agrega los stocks del producto al modal
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
                                                                <th scope="col" class="text-center">Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center">${product.stock_depot}</td>
                                                                <td class="text-center">${product.stock_local}</td>
                                                                <td class="text-center">${product.stock_truck}</td>
                                                                <td class="text-center">${product.stock_total}</td>
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
         * Agregar los datos base del producto al header del modal
         */
        function setProductModalHeaderInfo(product) {
            modal_product.find('.product-name').text(product.name);
            modal_product.find('.product-code').text(product.real_code);
            modal_product.find('.product-category').text(product.category ? product.category.name : '-');
            modal_product.find('.product-brand').text(product.brand ? product.brand.name : '-' );
        }
        
        /**
         * Retorna HTML string, con la tabla de los stocks del producto
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
         * Retorna HTML string con el header para la tabl ade los stocks del producto
         * Se valida si el producto tiene combinaciones o no
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
         * Retorna HTML string con el tbody de los stocks del producto
         * Se valida si el producto es regular o con combinaciones
         * Si tiene product.id es que es una combinacion
         */
        function getHtmlTableBodyProductStocks(product) {
            var html = '<tbody>';

            if (!product.product_id) {
                html = `<tr>
                            <th scope="row">${product.regular_price_str}</th>
                            <td>${product.stock_user}</td>
                            <td>
                                <div class="form-group">
                                    <input id="product-modal-input" class="form-control modal-product-input" type="number" min="0"  max="${product.stock_user}"  step="any" data-id="${product.id}" data-stock="${product.stock_user}" value="1">
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
                                <input id="product-modal-input" class="form-control modal-product-input" type="number" min="0"  max="${product.stock_user}"  step="any" data-id="${product.id}" data-stock="${product.stock_user}" value="1">
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
         * Agregar producta a vender a la datatable
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
         * Agrega al datatable de productos, el nuevo producto que se agrega a la venta
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
         * Retorna el producto seleccionado a agregar a la venta del array de productos del cliente seleccionado
         */
        function getProductFromArray(product_id) {
            return $products.find(obj => {
                return obj.id === product_id
            });
        }

        /**
         * Valida si una cantidad agregada a es valida considerando el stock disponible
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
         * Actualiza el datatable del resumen de la venta.
         * Cuando se actualiza un producto en el paso 'Productos'
         * Se actualiza la cantidad en el datatable del resumen
         */
        function updateDatatableResumeProductQty(product_id, new_qty)  {
            var tr = $(`.tr-product-${product_id}`);
            var data = datatable_products_resume.row(tr).data();
            data[8] = new_qty;
            datatable_products_resume.row(tr).data(data).draw();
        }

        /**
         * Peticion HTTP para calcular descuento de la venta
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

        /**
         * Captura evento para abrir o cerrar los detalles y actualizar el input child de su tr padre (Input input-product-qty original)
         * 
         * Esto se hace porque si se modifica el datatable por responsive, los inputs se replican y pueden perderse porque el plugin oculta el input original
         */
        datatable_products.on('click', 'tr', function () {
            var tr = $(this).closest('tr');
            var row = datatable_products.row(tr);
            var open = row.child.isShown();
            var input_qty = $(tr).find('.input-product-qty'),
                input_name = input_qty.attr('name');

            $('input[name="' + input_name + '"]').val(input_qty.val());
        });

        /**
         * Captura evento de cambio de cantidad de un stock
         * Se valida si ingresa un valor superior al maximo disponible para ese producto
         */
        $('body').on('change', '.input-product-qty', function(e) {
            var stock = Number($(this).data('stock')),
                product_name = $(this).data('name'),
                product_id = $(this).data('id'),
                val = Number($(this).val()),
                qty_final = val,
                input_name = $(this).attr('name');

            if (val < 0 || isNaN(val))  {
                qty_final = 1;
                $(this).val(1);
                $('input[name="' + input_name + '"]').val(1);
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

            // $('input[name="' + input_name + '"]').val(val);
            updateOrderTotal();
            updateDatatableResumeProductQty(product_id, qty_final);
        });

        /**
         * Captura evento para eliminar un producto de la venta
         * Luego se actualiza el total de la venta
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

        /**
         * Captura evento de swtich de nueva visita
         * Muestra o oculta los campos para agenda nueva visita
         */
        $('#enable_new_visit').change(function() {
            if (this.checked) {
                $('#visit-fields').removeClass('d-none');
            } else {
                $('#visit-fields').addClass('d-none');
            }
        });

        /**
         * Valida si el cliente necesita pautar una nueva visita debido a su balance (Si esta en negativo)
         */
        function checkNeedsVisit() {
            let result = $customer_balance;
            let orderTotal = getOrderTotal();
            let payment_method = $("input[name='payment_method']:checked").val();

            if (payment_method == 'credit') {
                result = $customer_balance - orderTotal.total;
            }

            if (result < 0) {
                $visit_container.removeClass('d-none');
            } else {
                $visit_container.addClass('d-none');
            }
        }

        // Utilities
        /**
         * Retorna un valor numerico en formato con , y . en los decimales, milesimas correspondientemente
         */
        function replaceNumberWithCommas(number) {
            var n = number.toFixed(2); // Limita el resultado a dos decimales
            //Seperates the components of the number
            var n= number.toString().split(".");
            //Comma-fies the first part
            n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            n[1] = n[1] ? n[1].slice(0, 2) : "00";
            //Combines the two sections
            return n.join(",");
        }

        /**
         * Inicializa datepicker para una futura visita
         */
        function setDatePicker() {
            var inputs = $('.datepicker-form');

            inputs.each((index, element) => {
                var value = element.value;

                if (value) {
                    var dateParts = value.split("-");
                    var date = dateParts[2] + "/" +  dateParts[1] + "/" + dateParts[0];
                } else {
                    var date = new Date(value);
                }

                $(element).datepicker({
                    format: "dd-mm-yyyy",
                    todayBtn: "linked",
                    language: "es",
                    autoclose: true,
                    todayHighlight: true,
                    showOnFocus: true,
                }).datepicker("setDate", date)
                .end().on('keypress paste', function (e) {
                    // e.preventDefault();
                    // return false;
                });
            });
        }

        /**
        * Steps form
        */
        var current_fs, next_fs, previous_fs; //fieldsets
        var opacity;
        
        /**
         * Captura evento para avanzar al siguiente paso del formulario de venta
         */
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
                    $('#datatable_products').DataTable()
                                    .columns.adjust()
                                    .responsive.recalc();
                } else if (step == 3) {
                    $('#datatable_products_resume').DataTable()
                                    .columns.adjust()
                                    .responsive.recalc();

                    checkNeedsVisit();
                }
            }
        });

        /**
         * Captura evento para regresar al paso anterior
         */
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

        /**
         * Captura evento de cambio de metodo de pago
         */
        $('.radio-group .radio').click(function(){
            $(this).parent().find('.radio').removeClass('selected');
            $(this).parent().find('input').prop("checked", false);
            $(this).addClass('selected');
            $(this).find('input').prop("checked", true);
        });

        /**
         * Valida si puede avanzar al siguiente paso del formulario de venta
         */
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