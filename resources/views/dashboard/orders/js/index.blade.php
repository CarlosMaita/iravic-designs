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

    /**
     * Archive order function
     */
    function archiveOrder(orderId) {
        swal({
            title: '¿Estás seguro?',
            text: "¿Deseas archivar esta orden?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, archivar',
            cancelButtonText: 'Cancelar'
        }).then(function () {
            $.ajax({
                url: `/admin/ordenes/${orderId}/archive`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        swal(
                            '¡Archivada!',
                            response.message,
                            'success'
                        );
                        $('#datatable_orders').DataTable().ajax.reload();
                    } else {
                        swal(
                            'Error',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    swal(
                        'Error',
                        response ? response.message : 'Error al archivar la orden',
                        'error'
                    );
                }
            });
        }).catch(swal.noop);
    }
</script>