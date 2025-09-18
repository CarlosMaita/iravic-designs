<script>
    $(function () {
        const URL_RESOURCE = "{{ route('admin.payments.index') }}";
        const DATATABLE_RESOURCE = $("#datatable_payments");

        /**
        * Inicializa la datatable de pagos
        */
        const dataTable = DATATABLE_RESOURCE.DataTable({
                fixedHeader: true,
                processing: false,
                responsive: true,
                serverSide: true,
                ajax: {
                    url: URL_RESOURCE,
                    data: function (d) {
                        const val = $('#status_filter').val();
                        if (val) {
                            d.status = val;
                        } else {
                            // Ensure no stale filter is sent
                            if (d.status) delete d.status;
                        }
                    }
                },
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

        // Filter by status, reset to first page
        $('#status_filter').on('change', function() {
            dataTable.page(0).draw('page');
        });
    });

    function verifyPayment(paymentId) {
        swal({
            title: '¿Verificar Pago?',
            text: "Confirme que ha validado la información del pago",
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, verificar',
            cancelButtonText: 'Cancelar'
        }).then(function () {
            $.ajax({
                url: `/admin/pagos/${paymentId}/verify`,
                method: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                    if (response.success) {
                        $('#datatable_payments').DataTable().ajax.reload();
                        new Noty({
                            text: response.message,
                            type: 'success'
                        }).show();
                    }
                },
                error: function() {
                    new Noty({
                        text: 'Error al verificar el pago',
                        type: 'error'
                    }).show();
                }
            });
        }).catch(swal.noop);
    }

    function rejectPayment(paymentId) {
        swal({
            title: '¿Rechazar Pago?',
            text: "Esta acción marcará el pago como rechazado",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, rechazar',
            cancelButtonText: 'Cancelar'
        }).then(function () {
            $.ajax({
                url: `/admin/pagos/${paymentId}/reject`,
                method: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                    if (response.success) {
                        $('#datatable_payments').DataTable().ajax.reload();
                        new Noty({
                            text: response.message,
                            type: 'success'
                        }).show();
                    }
                },
                error: function() {
                    new Noty({
                        text: 'Error al rechazar el pago',
                        type: 'error'
                    }).show();
                }
            });
        }).catch(swal.noop);
    }
</script>