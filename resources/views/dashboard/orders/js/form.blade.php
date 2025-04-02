<script>
    let $visit_container = $('#new-visit-container');
    let $customer_balance = 0;
    let $customer_max_credit = 0;

    $(function(){
        const FORM_RESOURCE_ORDERS = $("#form-orders");
        const URL_PRODUCTS = "{{ route('productos.index') }}";
        const URL_ORDER_DISCOUNT = "{{ route('ventas.discount') }}";
        const URL_CUSTOMER = "{{ route('clientes.index') }}";
        const CAN_PRICES_PER_METHOD_PAYMENT = "{{ empty(auth()->user()->can('prices-per-method-payment')) ? 0:1}}"; 
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
        let positive_balance_numeric = 0;
        let payment_method_selected = 'cash';
        let productsList = [];

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
                        if (product && product.stock_total > 0) {
                            handleShowProductForm(product);
                        } else if (product) {
                            swal({
                                title: 'Sin stock asociado',
                                html: `<div class="d-inline-flex flex-column text-left">
                                            <p class="mb-0">Stock Total: ${product.stock_total}</p>
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

            var $productInputs = $('#product-add-stocks').find('.product-input');

            $productInputs.each((i, productInput) => {
                const   id = $(productInput).data('id'),
                        storeId = $(productInput).data('store-id'),
                        storeName = $(productInput).data('store-name'),
                        storeStock = $(productInput).data('stock'),
                        value = Number($(productInput).val());

                if (isProductValid(storeStock, value) && value > 0) {
                    // verificar si el producto ya se encuentra en la lista
                    if( productsList.find(product => product.product_id === id && product.store_id === storeId) ) {
                        removeQuantityToProductDatatable( product_id = id, store_id = storeId);
                        const index = productsList.findIndex(product => product.product_id === id && product.store_id === storeId);
                        if (index > -1) {
                            productsList.splice(index, 1);
                        }
                    }

                    // agregar agregar producto
                    addProductToDatatable(id, { storeId, storeName, storeStock}, value);
                    productsList.push({product_id: id, store_id: storeId, quantity: value}) 
                    updateOrderTotal();
                    
                }
            });
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
            e.preventDefault();
            //get customer data from ajax   
            var selected = $(this).find(':selected');
            let customer_id = selected.data('id');
            getCustomerDataAjax(customer_id);
        });

        /**
         * Retorna la data del customer mediante una peticion ajax
        */
        function getCustomerDataAjax(customer_id){
            
            return $.ajax({
                url: `${URL_CUSTOMER}/${customer_id}`,
                type: "GET",
                processData: false,
                contentType: false,
                success: function(res) {
                    let container = $('#customer-selected-container');
                    let address = res.address;
                    let balance = res.balance;
                    let balance_numeric = res.balance_numeric;
                    let dni = res.dni;
                    let maxcredit = res.max_credit;
                    let maxcredit_str = res.max_credit_str;
                    let availablecredit_str = res.available_credit_str;
                    let name = res.name;
                    let email = res.email ?? 'Sin correo registrado';
                    let qualification = res.qualification;
                    let telephone = res.telephone;

                    $customer_balance = balance;
                    $customer_max_credit = maxcredit;
                    positive_balance_numeric = balance_numeric > 0 ? balance_numeric : 0;

                    container.find('#selected-customer-address').text(address);
                    container.find('#selected-customer-balance').text(balance);
                    container.find('#selected-customer-dni').text(dni);
                    container.find('#selected-customer-maxcredit').text(maxcredit_str);
                    container.find('#selected-customer-name').text(name);
                    container.find('#selected-customer-email').text(email);
                    container.find('#selected-customer-qualification').text(qualification);
                    container.find('#selected-customer-telephone').text(telephone);
                    container.removeClass('d-none');

                    $('.max-credit').text(maxcredit_str);
                    $('.customer-balance').text(balance);
                    $('.positive-balance').text(balance_numeric > 0 ?  balance : '$ 0,00');
                    $('.available-credit').text(availablecredit_str)
                }
            });
        }

        /**
         * Retorna el total de la venta
         */
        function getOrderTotal( ) {
            var subtotal = 0,
                total = 0;

            $('.input-product-qty').not("tr.child .input-product-qty").each(function(index, item) {
                
                const price = $(item).data('price'),
                      priceCardCredit = $(item).data('price-card-credit'),
                      priceCredit = $(item).data('price-credit'),
                      val = Number(item.value);
                let finalPrice = price;
                if (CAN_PRICES_PER_METHOD_PAYMENT == true) {
                    if(payment_method_selected == "card") {
                        finalPrice = priceCardCredit;
                    } else if(payment_method_selected == "credit") {
                        finalPrice = priceCredit;
                    }
                }
                

                subtotal += (finalPrice * val);
            });

            return {
                'subtotal': subtotal,
                'total'   : subtotal - discount_to_apply ,
                'total_to_collection': subtotal - discount_to_apply - positive_balance_numeric
            };
        }

        /**
         * Actualiza los textos de subtotal y total de la venta
         * Los totales son obtenidos de la funcion getOrderTotal
         */
        function updateOrderTotal() {
            var totals = getOrderTotal();
            $('.subtotal').text(`$ ${replaceNumberWithCommas(totals.subtotal)}`);
            $('.total').text(`$ ${ replaceNumberWithCommas(totals.total) }`);
            if ( positive_balance_numeric > 0 ) {
              
                const formattedTotalToCollection =  totals.total_to_collection >= 0 ?  replaceNumberWithCommas(totals.total_to_collection) :  `0,00 (${replaceNumberWithCommas(totals.total_to_collection)})`; ;
                $('.total_to_collection').text(`$ ${formattedTotalToCollection}`);
                $('.total_to_collection').addClass('text-danger');
            }else{
                $('.total_to_collection').addClass('d-none');
            }
            $("#total-to-collection").val( totals.total_to_collection);
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
            modal_product.modal('show');

            $('#datatable_stocks').DataTable({
                responsive: true
            });
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
                table_body = '';

            product.stores.forEach(store => {
                   const {id, name} = store;
                   const stock = store.pivot.stock;
                   table_body += getHtmlTableBodyProductStocks(product, id, name, stock);
            })

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
                                <th scope="col" style="width: 25%;">Precio</th>
                                <th scope="col" style="width: 25%;">Deposito</th>
                                <th scope="col" style="width: 25%;">Stock</th>
                                <th scope="col" style="width: 25%;">Cantidad</th>
                            </tr>
                        </thead>`;
            } else {
                html = `<thead>
                            <tr>
                                <th scope="col" style="width: 16.66%;">Color</th>
                                <th scope="col" style="width: 16.66%;">Talla</th>
                                <th scope="col" style="width: 16.66%;">Precio</th>
                                <th scope="col" style="width: 16.66%;">Deposito</th>
                                <th scope="col" style="width: 16.66%;">Stock</th>
                                <th scope="col" style="width: 16.66%;">Cantidad</th>
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
        function getHtmlTableBodyProductStocks(product, store_id = null, store_name=null, store_stock=null) {
            var html = '<tbody>';
            if (!product.product_id) {
                html = `<tr>
                            <th scope="row">${product.regular_price_str}</th>
                            <td>${store_name}</td>
                            <td>${store_stock}</td>
                            <td>
                                <div class="form-group">
                                    <input  id="product-modal-input"
                                            class="form-control modal-product-input product-input" 
                                            type="number" 
                                            min="0"  
                                            max="${store_stock}" 
                                            step="any" 
                                            data-id="${product.id}" 
                                            data-store-id="${store_id}" 
                                            data-store-name="${store_name}" 
                                            data-stock="${store_stock}" 
                                            value="0">
                                </div>
                            </td>
                        </tr>`;
            } else {
                 html += `<tr>
                            <th scope="row">${product.color?.name}</th>
                            <td>${product.size?.name}</td>
                            <td>${product.regular_price_str}</td>
                            <td>${store_name}</td>
                            <td>${store_stock}</td>
                            <td>
                                <div class="form-group">
                                    <input  id="product-modal-input" 
                                            class="form-control modal-product-input product-input"
                                            type="number" 
                                            min="0"  
                                            max="${store_stock}"  
                                            step="any" 
                                            data-id="${product.id}" 
                                            data-store-id="${store_id}" 
                                            data-store-name="${store_name}" 
                                            data-stock="${store_stock}"
                                            value="0">
                                </div>
                            </td>
                        </tr>`;
            }
            html += '</tbody>';

            return html;
        }

        /**
         * Agregar producta a vender a la datatable
         */
        function addProductToDatatable(product_id, store, value) {
            var product = getProductFromArray(product_id);

            if (product) {
                appendProductToProductsDatatable(product, store, value);
                // select_product.find('option[value="' + product_id + '"]').prop('disabled', true); //desabilitar producto seleccionado
                select_product.select2();
                select_product.val('Seleccionar').trigger('change');
                modal_product.modal('hide');
            }
        }

        /**
         * Agrega al datatable de productos, el nuevo producto que se agrega a la venta
         */
        function appendProductToProductsDatatable(product, store, value, update = false) {
            const { storeId, storeName, storeStock} = store;

            const row = datatable_products.row.add( [
                product.name,
                product.real_code,
                product.gender,
                product.brand ? product.brand.name : '',
                product.category ? product.category.name : '',
                product.color ? product.color.name : '-',
                product.size ? product.size.name : '-',
                product.regular_price_str,
                storeStock,
                storeName,
                `<input name="qtys[${product.id}][${storeId}]" 
                        class="form-control input-product-qty" 
                        type="number" min="0" max="${storeStock}" step="1"
                        data-id="${product.id}" 
                        data-store-id="${storeId}"
                        data-name="${product.name}"
                        data-price="${product.regular_price}"
                        data-price-card-credit="${product.regular_price_card_credit}" 
                        data-price-credit="${product.regular_price_credit}" 
                        data-stock="${storeStock}" 
                        value="${value}">`,
                `<input type="hidden" name="products[${product.id}][${storeId}]" value="${product.name}">
                <button type="button" 
                    data-id="${product.id}" 
                    data-name="${product.name}"
                    data-store-id="${storeId}"
                    data-toggle="tooltip" 
                    class="btn btn-sm btn-danger btn-action-icon remove-product" 
                    title="Eliminar" 
                    style="width: auto;">
                        <i class="fas fa-trash-alt"></i>
                </button>`
            ]).draw(false)
            .node();

            $(row).addClass(`tr-product-${product.id}-store-${storeId}`);

            // Resume.. Confirm Step
            const row_resume = datatable_products_resume.row.add( [
                product.name,
                product.real_code,
                product.gender,
                product.brand ? product.brand.name : '',
                product.category ? product.category.name : '',
                product.color ? product.color.name : '-',
                product.size ? product.size.name : '-',
                product.regular_price_str,
                storeName,
                value
            ])
            .draw(false)
            .node();

            $(row_resume).addClass(`tr-product-${product.id}-store-${storeId}`);
        }
        
        /**
         * Elimina la fila de un producto en la datatable de productos
         */
        function removeQuantityToProductDatatable(product_id, store_id) {
            var tr = $(`.tr-product-${product_id}-store-${store_id}`);
            var row = datatable_products.row(tr), 
                row_resume = datatable_products_resume.row(tr);
            row.remove().draw();
            row_resume.remove().draw();
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
            if(value<=0)
                return false;

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
        function updateDatatableResumeProductQty(product_id, store_id, new_qty)  {
            var tr = $(`.tr-product-${product_id}-store-${store_id}`);
            var data = datatable_products_resume.row(tr).data();
            data[9] = new_qty; // update qty
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
                store_id = $(this).data('store-id'),
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
            updateDatatableResumeProductQty(product_id,store_id, qty_final);
        });

        /**
         * Captura evento para eliminar un producto de la venta
         * Luego se actualiza el total de la venta
         */
        $('body').on('click', 'tbody .remove-product', function (e) {
            var product_id = $(this).data('id'),
                store_id = $(this).data('store-id'),
                name = $(this).data('name'),
                tr = $(this).parents('tr'),
                tr_resumen = $(`.tr-product-${product_id}-store-${store_id}`);

            datatable_products.row(tr).remove().draw();
            datatable_products_resume.row(tr_resumen).remove().draw();
            // select_product.find('option[value="' + product_id + '"]').prop('disabled', false); //desabilitar producto seleccionado
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
            $(this).data('value') == 'credit' ? $('#credit-info').removeClass('d-none') : $('#credit-info').addClass('d-none');
            if (positive_balance_numeric > 0 && $(this).data('value') == 'credit') {
                $('.positive-balance').parent().removeClass('d-none');
                $('.total_to_collection').parent().removeClass('d-none');
            }else{
                $('.positive-balance').parent().addClass('d-none');
                $('.total_to_collection').parent().addClass('d-none');
            }
            // actualizar el metodo de pago
            payment_method_selected = $(this).data('value');
            updateOrderTotal();
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
                if ($("input[name='payment_method']:checked", '#form-orders').val() == 'credit') {
                    if (!$('#start-quotas').val()) {
                        new Noty({
                            text: "Debe ingresar la fecha de inicio de cobro.",
                            type: 'error'
                        }).show();
                        return false;
                    }
                   
                    if (!$("input[name='amount-quotas']").val() ) {
                        new Noty({
                            text: "Debe ingresar la cantidad de cuotas.",
                            type: 'error'
                        }).show();
                        return false;
                    }
                    if ($("input[name='amount-quotas']").val() <= 0) {
                        new Noty({
                            text: "La cantidad de cuotas debe ser mayor a 0.",
                            type: 'error'
                        }).show();
                        return false;
                    }

                }
                
            }

            return true;
        }
    });
</script>