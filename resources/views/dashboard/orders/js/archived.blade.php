<script>
    $(function () {
        const URL_RESOURCE = "{{ route('admin.orders.archived') }}";
        const DATATABLE_RESOURCE = $("#datatable_archived_orders");

        /**
        * Inicializa la datatable de órdenes archivadas
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

    /**
     * Unarchive order function
     */
    function unarchiveOrder(orderId) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¿Deseas desarchivar esta orden?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, desarchivar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/ordenes/${orderId}/unarchive`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                '¡Desarchivada!',
                                response.message,
                                'success'
                            );
                            $('#datatable_archived_orders').DataTable().ajax.reload();
                        } else {
                            Swal.fire(
                                'Error',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        Swal.fire(
                            'Error',
                            response ? response.message : 'Error al desarchivar la orden',
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>