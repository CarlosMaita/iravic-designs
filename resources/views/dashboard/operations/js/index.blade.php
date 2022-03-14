<script>
    $(function () {
        const   URL_RESOURCE = "{{ route('operaciones.index') }}";

        $('#account-status-tab').on('click', function(e) {
            setTimeout(function(e) {
                $datatable_operations.DataTable()
                                    .columns.adjust()
                                    .responsive.recalc();
            }, 1000);
        });

        initDataTable();

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
                    {data: 'comentario'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        }

        function getUrlPaymentParams() {
            var params = '';

            if (typeof $customer !== 'undefined') {
                params += `?customer=${$customer.id}`;
            }

            return params;
        }
    });
</script>