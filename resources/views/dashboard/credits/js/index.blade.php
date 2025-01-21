<script>
    $(function () {
        const   URL_RESOURCE = "{{ route('creditos.index') }}",
                DATATABLE_RESOURCE = $("#datatable_credits");

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
                    {data: 'id' , name: 'id' , visible: false},
                    {data: 'order.customer.name'},
                    {data: 'start_date'},
                    {data: 'amount_quotas'},
                    {data: 'quota_formatted'},
                    {data: 'total_formatted'},
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

        /** 
         * Delete collection 
         * 
         * */
        $('body').on('click', 'tbody .delete-collection', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let token = $("input[name=_token]").val();
            let url = `${URL_RESOURCE}/${id}`;
            
            swal({
                title: '',
                text: "{{ __('dashboard.general.delete_resource') }}",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No'
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: token,
                            _method: 'DELETE'
                        },
                        success: function (data) {
                            DATATABLE_RESOURCE.DataTable().ajax.reload();
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
            });
        });

      

    });
</script>