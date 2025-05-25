<script>
    $(function () {
        const URL_RESOURCE = "{{ route('operaciones.index') }}";

        initDataTable();

        /**
         * Captura evento de click en la pestana estado de cuenta
         * Espera 1 segundo para ajustar el tamano del datatable
         * Cuando el datatable no esta visible y es creado, no configura bien el width
         */
        $('#account-status-tab').on('click', function(e) {
            setTimeout(function(e) {
                $datatable_operations.DataTable()
                                    .columns.adjust()
                                    .responsive.recalc();
            }, 1000);
        });

        /**
         * Inicializa datatable de estado de cuenta/operaciones.
         */
        function initDataTable() {
            var url_params = getUrlPaymentParams();

            $datatable_operations.DataTable({
                fixedHeader: true,
                processing: false,
                responsive: true,
                serverSide: true,
                ajax: URL_RESOURCE + url_params,
                pageLength: 25,
                ordering: false,
                columns: [
                    {data: 'date'},
                    {data: 'amount'},
                    {data: 'type'},
                    {data: 'balance'},
                    {
                        render: function (data, type, row) {
                            let comment = '';
                            
                            if (Array.isArray(row.comment)) {
                                row.comment.forEach((value, index) => {
                                    comment += `<p class="mb-0"><b>-</b> ${value}</p>`;
                                });
                            } else if (row.comment) {
                                comment = row.comment;
                            }

                            return comment;
                        }
                    },
                ]
            });
        }

        /**
         * Retorna string para ser usado como query parametro con el cliente seleccionado 
         */
        function getUrlPaymentParams() {
            var params = '';

            if (typeof $customer !== 'undefined') {
                params += `?customer=${$customer.id}`;
            }

            return params;
        }
    });
</script>