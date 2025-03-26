<script>
    $(function () {
        const URL_RESOURCE = "{{ route('clientes.pendiente.agendar') }}";
        const DATATABLE_RESOURCE = $("#datatable_customers_pending_to_schedule");

        /**
         * Inicializa datatable de clientes deudores
         */
         DATATABLE_RESOURCE.DataTable({
                fixedHeader: true,
                processing: false,
                responsive: true,
                serverSide: true,
                ajax: URL_RESOURCE,
                pageLength: 10,
                columns: [
                    {data: 'name'},
                    {data: 'dni'},
                    {data: 'telephone'},
                    {data: 'qualification', searchable: false},
                    {data: 'zone.name'},
                    {data: 'balance', searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
    });
</script>