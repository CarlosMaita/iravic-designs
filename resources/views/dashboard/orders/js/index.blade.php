<script>
    $(function () {
        const URL_RESOURCE = "{{ route('admin.orders.index') }}";
        const DATATABLE_RESOURCE = $("#datatable_orders");

        /**
        * Inicializa la datatable de órdenes
        */
        DATATABLE_RESOURCE.DataTable({
                fixedHeader: true,
                processing: false,
                responsive: true,
                serverSide: true,
                ajax: URL_RESOURCE,
                pageLength: 10,
                searchDelay : 1000,
                order: [[0, 'desc']],
                columns: [
                    {data: 'id'},
                    {data: 'customer_name'},
                    {data: 'status_badge', orderable: false, searchable: false},
                    {data: 'total_formatted', searchable: false},
                    {data: 'date_formatted', searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
        });
    });
</script>