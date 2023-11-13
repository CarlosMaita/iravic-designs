<script>
    $(function () {
        const URL_RESOURCE = "{{ route('clientes.pendiente.agendar') }}";
        const DATATABLE_RESOURCE = $("#datatable_customers_pending_to_schedule");

        initDataTable();

        /**
         * Inicializa datatable de clientes deudores
         */
        function initDataTable() {
            DATATABLE_RESOURCE.DataTable({
                fixedHeader: true,
                processing: false,
                responsive: true,
                serverSide: true,
                ajax: URL_RESOURCE,
                pageLength: 25,
                columns: [
                    {data: 'name'},
                    {data: 'dni'},
                    {data: 'telephone'},
                    {data: 'qualification'},
                    {data: 'zone.name'},
                    {data: 'balance'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        }
    });
</script>