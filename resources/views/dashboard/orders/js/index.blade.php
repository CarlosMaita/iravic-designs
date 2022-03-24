<script>
    $(function () {
        const URL_RESOURCE = "{{ route('ventas.index') }}";
        const DATATABLE_RESOURCE = $("#datatable_orders");

        initDataTable();

        /**
         * Inicializa datatable de ventas
         */
        function initDataTable() {
            DATATABLE_RESOURCE.DataTable({
                fixedHeader: true,
                processing: false,
                responsive: true,
                serverSide: true,
                ajax: URL_RESOURCE,
                pageLength: 25,
                ordering: false,
                columns: [
                    {data: 'id'},
                    {data: 'customer.name'},
                    {data: 'date'},
                    {data: 'payment_method'},
                    {data: 'total'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        }
    });
</script>