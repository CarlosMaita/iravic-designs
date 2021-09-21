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
         * 
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
         * 
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
         * 
         */
        form_advanced_search.on('submit', function(e) {
            e.preventDefault();
            
            localStorage.setItem('brand', $('#brand').val());
            localStorage.setItem('category', $('#category').val()),
            localStorage.setItem('color', $('#color').val()),
            localStorage.setItem('gender', $('#gender').val()),
            localStorage.setItem('size', $('#size').val()),
            localStorage.setItem('price_from', $('#price-from').val()),
            localStorage.setItem('price_to', $('#price-to').val());
            DATATABLE_RESOURCE.DataTable().ajax.reload();
        });

        /**
         * 
         */
        btn_advanced_search.click(function() {
            if (advanced_search.hasClass('show')) {
                advanced_search.collapse('hide');
            } else {
                advanced_search.collapse('show');
            }
        });

        /**
         * 
         */
        btn_clear_filter.click(function() {
            select_brand.val('Todas').trigger('change');
            select_category.val('Todas').trigger('change');
            select_gender.val('Todos').trigger('change');
            select_color.val('Todos').trigger('change');
            select_size.val('Todas').trigger('change');
        });

        /**
         * 
         */
        btn_close_filter.click(function() {
            advanced_search.collapse('hide');
        });
        
        /**
        *
        */
        btn_download.click(function(e) {
            e.preventDefault();
            var data = form_advanced_search.serialize();
            window.location = `${URL_DOWNLOAD}?${data}`;
        });

        /**
        *
        */
        modal_stocks_qty.on('hidden.coreui.modal', function(e) {
            modal_stocks_qty.find('.modal-title span').text('');
            modal_stocks_qty.find('.modal-body').empty();
        });


        /**
        *
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
         * 
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
                pageLength: 25,
                columns: [
                    {data: 'name'},
                    {data: 'code'},
                    {data: 'gender'},
                    {data: 'brand.name'},
                    {data: 'category.name'},
                    // {
                    //     render: function (data, type, row) {
                    //         return row.is_regular ? 'No' : 'Si';
                    //     }
                    // },
                    {data: 'regular_price_str'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        }

        /**
         * 
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
         * 
         */
        function getModalStocksContentHtml(product) {
            if (!product.is_regular) {
                return getCombinationsStockContent(product);
            }
            
            return getRegularStockContent(product);
        }

        /**
         * 
         */
        function getRegularStockContent(product) {
            var html = `<div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="datatable_stocks" class="table" width="100%">
                                            <thead>
                                                <tr>
                                                    @if (Auth::user()->isAdmin())
                                                        <th scope="col">Depósito</th>
                                                        <th scope="col">Local</th>
                                                        <th scope="col">Camión</th>
                                                        <th scope="col">Total</th>
                                                    @else
                                                        <th scope="col">Stock</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    @if (Auth::user()->isAdmin())
                                                        <td>${product.stock_depot}</td>
                                                        <td>${product.stock_local}</td>
                                                        <td>${product.stock_truck}</td>
                                                        <td>${product.stock_total}</td>
                                                    @else
                                                        <td>${product.stock_user}</td>
                                                    @endif
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
         * 
         */
        function getCombinationsStockContent(product) {
            var html = `<div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="datatable_stocks" class="table" width="100%">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Color</th>
                                                    <th scope="col">Talla</th>
                                                @if (Auth::user()->isAdmin())
                                                    <th scope="col">Depósito</th>
                                                    <th scope="col">Local</th>
                                                    <th scope="col">Camión</th>
                                                    <th scope="col">Total</th>
                                                @else
                                                    <th scope="col">Stock</th>
                                                @endif
                                                </tr>
                                            </thead>`;

            product.product_combinations.forEach(combination => {
                html += `<tr>
                            <td>${combination.color ? combination.color.name : ''}</td>
                            <td>${combination.size ? combination.size.name : ''}</td>`;

                @if (Auth::user()->isAdmin())
                    html += `<td>${combination.stock_depot}</td>
                            <td>${combination.stock_local}</td>
                            <td>${combination.stock_truck}</td>
                            <td>${combination.stock_total}</td>`;
                @else
                    html += `<td>${combination.stock_user}</td>`;
                @endif

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