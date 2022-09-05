<script>
    $(function() {
        const DATATABLE_IMAGES = $("#datatable_images");
        const URL_PRODUCT_IMAGES = "{{ route('producto-imagen.index') }}"
        const URL_HISTORY_STOCK = "{{ route('productos-stock-history.index') }}";

        let datatable_history = $('#datatable_history'),
            form_transfer = $('#stock-transfer-form'),
            form_modify = $('#stock-modify-stock-form'),
            modal_stock_history = $('#modal-history-stock'),
            modal_stock_modify = $('#modal-stock-modify'),
            modal_stock_transfer = $('#modal-stock-transfer'),
            product_modifying = null,
            product_viewing = null,
            stock_column_modifying = null,
            stock_column_viewing = null;

        initDatatableImages();
        initDatatableHistory();

        /**
         * Captura evento submit de formulario de transferencia de stock
         */
        form_transfer.on('submit', function(e) {
            e.preventDefault();
            var form = $('#stock-transfer-form')[0];
            var formData = new FormData(form);
            
            $.ajax({
                    url: form_transfer.attr('action'),
                    type: form_transfer.attr('method'),
                    enctype: 'multipart/form-data',
                    data: formData,
                    processData: false,
                    contentType: false,
                success: function (response) {
                    if (response.success) {
                        // var product = response.data.product_stock_transfer.product;
                        // $(`#product_${product.id}_stock_local`).val(product.stock_local);
                        // $(`#product_${product.id}_stock_truck`).val(product.stock_truck);
                        // $(`#btn_${product.id}_stock_local`).data('stock', product.stock_local);
                        // $(`#btn_${product.id}_stock_truck`).data('stock', product.stock_truck);
                        modal_stock_transfer.modal('hide');
                        location.reload();

                        // new Noty({
                        //     text: "La solicitud de transferencia se ha realizado con éxito.",
                        //     type: 'success'
                        // }).show();
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
         * Captura evento submit de formulario de actualizar stock del producto (Tipo)
         */
        form_modify.on('submit', function(e) {
            e.preventDefault();
            var form = $('#stock-modify-stock-form')[0];
            var formData = new FormData(form);
            
            $.ajax({
                    url: form_modify.attr('action'),
                    type: form_modify.attr('method'),
                    enctype: 'multipart/form-data',
                    data: formData,
                    processData: false,
                    contentType: false,
                success: function (response) {
                    if (response.success) {
                        modal_stock_modify.modal('hide');
                        location.reload();
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
         * Captura evento para mostrar modal de historial de stock
         * Actualiza datatable de historial
         */
        modal_stock_history.on('shown.coreui.modal', function(e) {
            datatable_history.DataTable({
                responsive: true
            })
            .columns.adjust()
            .responsive.recalc();
        });

        /**
         * Captura evento de cerrar odal de historial de stock
         */
        modal_stock_history.on('hidden.coreui.modal', function(e) {
            product_viewing = null;
            stock_column_viewing = null;
            modal_stock_history.find('.modal-title span').text('');
        });

        /**
         * Captura evento de cerrar modal de modificar/actualizar stock
         * Se vacian los campos del formulario de modificar stock
         */
        modal_stock_modify.on('hidden.coreui.modal', function(e) {
            form_modify.find("input[name='product_id']").val('');
            form_modify.find("input[name='stock_column']").val('');
            form_modify.find("input[name='stock_name']").val('');
            form_modify.find("input[name='stock']").val('');
            modal_stock_history.find('.stock-origin').text('');
        });

        /**
         * Captura evento de ocultar modal de transferencia de stock
         * Vacia los inputs del formulario de transferencia
         */
        modal_stock_transfer.on('hidden.coreui.modal', function(e) {
            modal_stock_transfer.find('input[name="product_id"]').val('');
            modal_stock_transfer.find('input[name="stock_origin"]').val('');
            modal_stock_transfer.find('input[name="stock_destination"]').val('');
            modal_stock_transfer.find('input[name="qty"]').val('');
            modal_stock_transfer.find('input[name="qty"]').attr('max', 0);
            modal_stock_transfer.find('#btn-max').attr('stock', 0);
            modal_stock_transfer.find('.stock-available').text('');
            modal_stock_transfer.find('.stock-origin').text('');
            modal_stock_transfer.find('.stock-destination').text('');
        });

        /**
         * Captura evento de ver historial de stock (Tipo) de un producto
         */
        $('.view-stock-history').on('click', function(e) {
            var product_id = $(this).data('id'),
                stock_column = $(this).data('stock'),
                stock_name = getStockName(stock_column);

            modal_stock_history.modal('show');
            modal_stock_history.find('.modal-title span').text(stock_name);
            product_viewing = product_id;
            stock_column_viewing = stock_column;
            datatable_history.DataTable().ajax.reload();
        });

        /**
         * Captura evento para modificar/actualizar stock
         */
        $('.modify-stock').on('click', function(e) {
            var product_id = $(this).data('id'),
                stock_qty = $(this).data('qty'),
                stock_column = $(this).data('stock-column'),
                stock_name = getStockName(stock_column);

            form_modify.find("input[name='product_id']").val(product_id);
            form_modify.find("input[name='stock_column']").val(stock_column);
            form_modify.find("input[name='stock_name']").val(stock_name);
            form_modify.find("input[name='stock']").val(stock_qty);
            modal_stock_modify.modal('show');
            modal_stock_modify.find('.stock-origin').text(stock_name);
        });

        /**
         * Captura evento para ver transferencia de stock
         */
        $('.view-transfer-stock').on('click', function(e) {
            var product_id = $(this).data('id'),
                stock = Number($(this).data('stock')),
                stock_origin = $(this).data('stock-origin'),
                stock_destination = $(this).data('stock-destination'),
                stock_name_origin = getStockName(stock_origin),
                stock_name_destination = getStockName(stock_destination);

            modal_stock_transfer.find('input[name="product_id"]').val(product_id);
            modal_stock_transfer.find('input[name="stock_origin"]').val(stock_origin);
            modal_stock_transfer.find('input[name="stock_destination"]').val(stock_destination);
            modal_stock_transfer.find('input[type="number"]').attr('max', stock);
            modal_stock_transfer.find('.stock-available').text(stock);
            modal_stock_transfer.find('#btn-max').attr('stock', stock);
            modal_stock_transfer.find('.stock-origin').text(stock_name_origin);
            modal_stock_transfer.find('.stock-destination').text(stock_name_destination);
            modal_stock_transfer.modal('show');
        });

        /**
         * Captura evento de maximo stock del producto.
         * Setea el stock del producto como el maximo en la transferencia modal
         */
        $('#btn-max').on('click', function(e) {
            var max = Number($(this).attr('stock'));
            modal_stock_transfer.find('input[name="qty"]').val(max);
        });

        /**
         * Inicializa datatable de imagenes del producto
         */
        function initDatatableImages() {
            DATATABLE_IMAGES.DataTable({
                fixedHeader: true,
                processing: false,
                responsive: true,
                serverSide: true,
                ajax: `${URL_PRODUCT_IMAGES}?producto={{ $product->id }}`,
                pageLength: 25,
                columns: [
                    {
                        render: function (data, type, row) {
                            var img = "<img class='img-fluid' src=\"" + row.url_img + "\" alt=\"\">";
                            return (img);
                        }
                    },
                ]
            });
        }
        
        /**
         * Inicializa datatable de historial de stock
         */
        function initDatatableHistory() {
            datatable_history.DataTable({
                fixedHeader: true,
                processing: false,
                responsive: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: `${URL_HISTORY_STOCK}`,
                    dataType: "json",
                    contentType: 'application/json',
                    data: function (d) {
                        d.product = product_viewing,
                        d.stock_column = stock_column_viewing
                    },
                },
                pageLength: 25,
                columns: [
                    {data: 'date'},
                    {data: 'qty'},
                    {data: 'old_stock'},
                    {data: 'new_stock'},
                    {data: 'action'},
                    {data: 'user.name'},
                ]
            });
        }

        /**
         * Retorna nombre en espanol del tipo de stock
         */
        function getStockName(stock_column) {
            var stock_name = '';

            switch (stock_column) {
                case 'stock_depot':
                    stock_name = 'Depósito';
                    break;
                case 'stock_local':
                    stock_name = 'Local';
                    break;
                case 'stock_truck':
                    stock_name = 'Camioneta';
                    break;
                default:
                    break;
            }

            return stock_name;
        }
    });
</script>