<script>
    $(function() {
        const DATATABLE_IMAGES = $("#datatable_images");
        const URL_PRODUCT_IMAGES = "{{ route('producto-imagen.index') }}"
        const URL_HISTORY_STOCK = "{{ route('productos-stock-history.index') }}";

        let datatable_history = $('#datatable_history'),
            modal_stock_history = $('#modal-history-stock'),
            product_viewing = null,
            stock_column_viewing = null;

        initDatatableImages();
        initDatatableHistory();

        /**
        *
        */
        modal_stock_history.on('shown.coreui.modal', function(e) {
            datatable_history.DataTable({
                responsive: true
            })
            .columns.adjust()
            .responsive.recalc();
        });

        /**
        *
        */
        modal_stock_history.on('hidden.coreui.modal', function(e) {
            product_viewing = null;
            stock_column_viewing = null;
            modal_stock_history.find('.modal-title span').text('');
        });

        /**
        *
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
        *
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
        *
        */
        function initDatatableHistory() {
            datatable_history.DataTable({
                fixedHeader: true,
                processing: false,
                responsive: true,
                serverSide: true,
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
                    {
                        render: function (data, type, row) {
                            if (row.order_product_id) {
                                return `Pedido: #${row.product_order.order_id}`;
                            }

                            return 'Actualización Stock';
                        }
                    },
                    {data: 'user.name'},
                ]
            });
        }

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