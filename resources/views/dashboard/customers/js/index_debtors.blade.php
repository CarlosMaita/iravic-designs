<script>
    $(function () {
        const URL_RESOURCE = "{{ route('clientes.debtors') }}";
        const DATATABLE_RESOURCE = $("#datatable_customers_debtors");

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
                searchDelay : 1000,
                columns: [
                    {data: 'name'},
                    {data: 'dni'},
                    {data: 'telephone'},
                    {data: 'qualification', searchable: false},
                    {data: 'zone.name'},
                    {data: 'balance', searchable: false},
                    {data: 'lastdatefordebt' },
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
    });
</script>