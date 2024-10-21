<script>
    $(function () {
        const   URL_RESOURCE = "{{ route('cobros.index') }}",
                DATATABLE_RESOURCE = $("#datatable_collections");

        initDataTable();

        /**
         * Inicializa datatable de cobros
         */
        function initDataTable() {
            DATATABLE_RESOURCE.DataTable({
                fixedHeader: true,
                processing: false,
                responsive: true,
                serverSide: true,
                ajax: URL_RESOURCE ,
                pageLength: 25,
                ordering: false,
                columns: [
                    {data: 'id'},
                    {data: 'start_date'},
                    {data: 'amount_quotas'},
                    {data: 'frequency'},
                    {data: 'quota'},
                    {data: 'balance'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        }

        /**
         * Captura evento de click en la pestana pagos
         * Espera 1 segundo para ajustar el tamano del datatable
         * Cuando el datatable no esta visible y es creado, no configura bien el width
         */
        $('#collections-tab').on('click', function(e) {
            setTimeout(function(e) {
                DATATABLE_RESOURCE.DataTable()
                .columns.adjust()
                .responsive.recalc();
            }, 1000);
        });

      

    });
</script>