<script>
    $(function () {
        const URL_RESOURCE = "{{ route('admin.payments.archived') }}";
        const DATATABLE_RESOURCE = $("#datatable_archived_payments");

        /**
        * Inicializa la datatable de pagos archivados
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
                    {data: 'order_number'},
                    {data: 'amount_formatted', searchable: false},
                    {data: 'status_badge', orderable: false, searchable: false},
                    {data: 'reference_formatted', searchable: false},
                    {data: 'date_formatted', searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
        });
    });

    /**
     * Unarchive payment function
     */
    function unarchivePayment(paymentId) {
        swal({
            title: '¿Estás seguro?',
            text: "¿Deseas desarchivar este pago?",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, desarchivar',
            cancelButtonText: 'Cancelar'
        }).then(function () {
            $.ajax({
                url: `/admin/pagos/${paymentId}/unarchive`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        swal(
                            '¡Desarchivado!',
                            response.message,
                            'success'
                        );
                        $('#datatable_archived_payments').DataTable().ajax.reload();
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
                        response ? response.message : 'Error al desarchivar el pago',
                        'error'
                    );
                }
            });
        }).catch(swal.noop);
    }
</script>