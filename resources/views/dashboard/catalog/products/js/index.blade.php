<script>
    $(function () {
        const DATATABLE_RESOURCE = $("#datatable_products");
        const URL_RESOURCE = "{{ route('productos.index') }}";
        const URL_DOWNLOAD = "{{ route('catalog.download') }}";

        let form_advanced_search = $('#form-advanced-search'),
            advanced_search = $('#adv-search'),
            btn_advanced_search = $('#btn-advanced-search'),
            btn_clear_filter = form_advanced_search.find('.clear-form'),
            btn_close_filter = form_advanced_search.find('#close-advance-search'),
            btn_download = $('#btn-download'),
            modal_stocks_qty = $('#modal-stock-qty')
            select_brand = $('#brand'),
            select_category = $('#category'),
            select_gender = $('#gender'),
            select_color = $('#color'),
            select_size = $('#size');

        $('select').select2({
            allowClear: true,
            placeholder: "Seleccionar"
        });

        initAdvanceFilterData();
        initDataTable();   

        /**
         * Captura evento de ver stocks de un stock
         * Realiza peticion HTTP para obtener stocks
         * Luego se manda actualizar los stocks del producto
         */
        $('body').on('click', 'tbody .btn-show-stock', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var url = `${URL_RESOURCE}/${id}?stocks=1`;
            
            $.ajax({
                url: url,
                type: 'GET',
                datatype: 'json',
                contentType: 'application/json',
                success: function (response) {
                    if (response) {
                        updateModalStocksContent(response);
                    } else {
                        new Noty({
                            text: "No se puede obtener información del producto en este momento.",
                            type: 'error'
                        }).show();
                    }
                },
                error: function (e) {
                    if (e.responseJSON.message) {
                        new Noty({
                            text: e.responseJSON.message,
                            type: 'error'
                        }).show();
                    } else {
                        new Noty({
                            text: "No se puede obtener información del producto en este momento.",
                            type: 'error'
                        }).show();
                    }
                }
            });
        });

        /**
         * Captura evento de eliminar producto
         * Realiza peticion HTTP
         */
        $('body').on('click', 'tbody .delete-resource', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let token = $("input[name=_token]").val();
            let url = `${URL_RESOURCE}/${id}`;
            
            swal({
                title: '',
                text: "{{ __('dashboard.general.delete_resource') }}",
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No'
            }).then(function () {
                $.ajax({
                    url: url,
                    headers: {'X-CSRF-TOKEN': token},
                    type: 'DELETE',
                    datatype: 'json',
                    success: function (response) {
                        if (response.success) {
                            DATATABLE_RESOURCE.DataTable().ajax.reload();
                            new Noty({
                                text: response.message,
                                type: 'success'
                            }).show();
                        } else if (response.message) {
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
                                text: "No se puede eliminar el producto en este momento.",
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
                        } else if (e.responseJSON.message) {
                            new Noty({
                                text: e.responseJSON.message,
                                type: 'error'
                            }).show();
                        } else {
                            new Noty({
                                text: "No se puede eliminar el producto en este momento.",
                                type: 'error'
                            }).show();
                        }
                    }
                });
            }).catch(swal.noop);
        });

        /**
         * Captura evento submit de busqueda avanzada para filtrar productos
         */
        form_advanced_search.on('submit', function(e) {
            e.preventDefault();
            
            localStorage.setItem('brand', $('#brand').val());
            localStorage.setItem('category', $('#category').val());
            localStorage.setItem('color', $('#color').val());
            localStorage.setItem('gender', $('#gender').val());
            localStorage.setItem('size', $('#size').val());
            localStorage.setItem('price_from', $('#price-from').val());
            localStorage.setItem('price_to', $('#price-to').val());
            DATATABLE_RESOURCE.DataTable().ajax.reload();
        });

        /**
         * Captura evento para abrir o cerrar formulario de busqueda avanzada
         */
        btn_advanced_search.click(function() {
            if (advanced_search.hasClass('show')) {
                advanced_search.collapse('hide');
            } else {
                advanced_search.collapse('show');
            }
        });

        /**
         * Captura evento para vaciar filtro de busqueda avanzada
         */
        btn_clear_filter.click(function() {
            select_brand.val('Todas').trigger('change');
            select_category.val('Todas').trigger('change');
            select_gender.val('Todos').trigger('change');
            select_color.val('Todos').trigger('change');
            select_size.val('Todas').trigger('change');

            localStorage.setItem('brand', null);
            localStorage.setItem('category', null);
            localStorage.setItem('color', null);
            localStorage.setItem('gender', null);
            localStorage.setItem('size', null);
            localStorage.setItem('price_from', null);
            localStorage.setItem('price_to', null);
        });

        /**
         * Captura evento para cerrar filtro de busqueda avanzada
         */
        btn_close_filter.click(function() {
            advanced_search.collapse('hide');
        });
        
        /**
         * Captura evento para descargar catalogo PDF
         * Redirecciona para descarga automaticamente
         */
        btn_download.click(function(e) {
            e.preventDefault();
            var data = form_advanced_search.serialize();
            window.location = `${URL_DOWNLOAD}?${data}`;
        });

        /**
         * Captura evento de cerrar modal de stocks de un producto
         */
        modal_stocks_qty.on('hidden.coreui.modal', function(e) {
            modal_stocks_qty.find('.modal-title span').text('');
            modal_stocks_qty.find('.modal-body').empty();
        });

        /**
         * Inicializa filtro de busqueda avanzada
         * Se guarda los datos en localstorage
         */
        function initAdvanceFilterData() {
            var brand = localStorage.getItem('brand'),
                category = localStorage.getItem('category'),
                color = localStorage.getItem('color'),
                gender = localStorage.getItem('gender'),
                size = localStorage.getItem('size'),
                price_from = localStorage.getItem('price_from'),
                price_to = localStorage.getItem('price_to');

            select_brand.val(brand ? brand.split(',') : 'Todas').trigger('change');
            select_category.val(category ? category.split(',') : 'Todas').trigger('change');
            select_color.val(color ? color.split(',') : 'Todos').trigger('change');
            select_gender.val(gender ? gender.split(',') : 'Todos').trigger('change');
            select_size.val(size ? size.split(',') : 'Todas').trigger('change');
            form_advanced_search.find("input[name='price_from']").val(price_from);
            form_advanced_search.find("input[name='price_to']").val(price_to);
        }

        /**
         * Inicializa datatable de productos
         */
        function initDataTable() {
            DATATABLE_RESOURCE.DataTable({
                fixedHeader: true,
                processing: false,
                responsive: true,
                serverSide: true,
                ajax: {
                    url: URL_RESOURCE,
                    data: function(d) {
                        d.brand      = $("#brand").val() ? $("#brand").val() : null;
                        d.category   = $("#category").val() ? $("#category").val() : null;
                        d.gender     = $("#gender").val() ? $("#gender").val() : null;
                        d.color      = $("#color").val() ? $("#color").val() : null;
                        d.size       = $("#size").val() ? $("#size").val() : null;
                        d.price_from = $("#price-from").val() ? $("#price-from").val() : null;
                        d.price_to   = $("#price-to").val() ? $("#price-to").val() : null;
                    },
                },
                pageLength: 10,
                columns: [
                    {data: 'image', name: 'image', orderable: false, searchable: false},
                    {data: 'name'},
                    {data: 'code'},
                    {data: 'gender'},
                    {data: 'brand.name'},
                    {data: 'category.name'},
                    {data: 'regular_price_str', searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        }

        /**
         * Actualiza contenido de stocks de producto en Modal
         */
        function updateModalStocksContent(product) {
            var html_content = getModalStocksContentHtml(product);
            modal_stocks_qty.modal('show');
            modal_stocks_qty.find('.modal-title span').text(product.name);
            modal_stocks_qty.find('.modal-body').append(html_content);

            
            $('#datatable_stocks').DataTable({
                responsive: true
            });
        }

        /**
         * Retorna contenido HTML de stocks para ser agregado al modal
         */
        function getModalStocksContentHtml(product) {
            if (!product.is_regular) {
                return getCombinationsStockContent(product);
            }
            
            return getRegularStockContent(product);
        }

        /**
         * Retorna el contenido de stocks de un producto que es regular (Sin combinaciones)
         */
        function getRegularStockContent(product) {
            const getStoresTitlesAndData = (stores) => {
                let storesTitles = '';
                let storesData = '';
                stores.forEach((store, index, array) => {
                    storesTitles += `<th scope="col">${store.name}</th>`
                    storesData += ` <td>${store.pivot.stock}</td>`
                })
                return {storesTitles, storesData };
            }
            let {storesTitles, storesData}  = getStoresTitlesAndData(product.stores);

              

            var html = `<div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="datatable_stocks" class="table" width="100%">
                                            <thead>
                                                <tr>
                                                    ${storesTitles}
                                                    <th scope="col">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    ${storesData}
                                                    <td>${product.stock_total}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>`;

            return html;
        }

        /**
         * Retorna el contenido de stocks de un producto con combinaciones
         */
        function getCombinationsStockContent(product) {
            
            const getStoresTitles = (stores) => {
                    let storesTitles = '';
                    stores.forEach((store, index, array) => {
                        storesTitles += `<th scope="col">${store.name}</th>`
                    })
                    return {storesTitles};
                }
            let {storesTitles}  = getStoresTitles(product.product_combinations[0].stores); 


            var html = `<div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="datatable_stocks" class="table" width="100%">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Color</th>
                                                    <th scope="col">Talla</th>
                                                    ${storesTitles}
                                                    <th scope="col">Total</th>
                                                </tr>
                                            </thead>`;

            product.product_combinations.forEach(combination => {

                const getStoresData = (stores) => {
                    let storesData = '';
                    stores.forEach((store, index, array) => {
                        storesData += ` <td>${store.pivot.stock}</td>`
                    })
                    return {storesData, storesData };
                }
                let {storesData}  = getStoresData(combination.stores);

                html += `<tr>
                            <td>${combination.color ? combination.color.name : ''}</td>
                            <td>${combination.size ? combination.size.name : ''}</td>`;
                html += storesData;
                html += `<td>${combination.stock_total}</td>`;
                html += `</tr>`;
            });            

            html += `               </table>
                                </div>
                            </div>
                        </div>
                    </div>`;

            return html;
        }
    });
</script>