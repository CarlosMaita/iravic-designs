<script>
    $(function () {
        const   URL_RESOURCE = "{{ route('deudas.index') }}",
                DATATABLE_RESOURCE = $("#datatable_debts");

        initDataTable();

        /**
         * Captura evento de click en la pestana pagos
         * Espera 1 segundo para ajustar el tamano del datatable
         * Cuando el datatable no esta visible y es creado, no configura bien el width
         */
        $('#debts-tab').on('click', function(e) {
            setTimeout(function(e) {
                DATATABLE_RESOURCE.DataTable()
                .columns.adjust()
                .responsive.recalc();
            }, 1000);
        });


        /**
         * Inicializa datatable de deudas
         */
        function initDataTable() {
            var url_params = getUrlPaymentParams();

            DATATABLE_RESOURCE.DataTable({
                fixedHeader: true,
                processing: false,
                responsive: true,
                serverSide: true,
                ajax: URL_RESOURCE + url_params,
                pageLength: 25,
                ordering: false,
                columns: [
                    {data: 'id'},
                    {data: 'date'},
                    {data: 'amount_str'},
                    {data: 'comment'},
                ]
            });
        }

        /**
         * Retorna string para ser usado como query parametro con el cliente o caja
         */
        function getUrlPaymentParams() {
            var params = '';

            if (typeof $customer !== 'undefined') {
                params += `?customer=${$customer.id}`;
            }

            if (typeof $box !== 'undefined') {
                params += `?box=${$box.id}`;
            }

            return params;
        }
    });
</script>