<script>
    $(function () {
        const URL_RESOURCE = "{{ route('stock-transferencias.index') }}";
        let datatable_resource = $("#datatable_transfers");

        initDataTable();

        /**
         * Inicializa datatable de transferencias
         */
        function initDataTable() {
            datatable_resource.DataTable({
                fixedHeader: true,
                processing: true,
                responsive: true,
                // serverSide: true,
                ajax: URL_RESOURCE,
                pageLength: 25,
                columns: [
                    {data: 'product.name_full', name: 'product.name'},
                    {data: 'qty'},
                    {data: 'stock_name_origin'},
                    {data: 'stock_name_destination'},
                    {data: 'creator.name'},
                    {data: 'responsable.name'},
                    {data: 'date', orderData: 6,},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ], 
                columnDefs: [
                {
                    targets: 6, // Index of the "date" column
                    type: 'date' // Specify the column type as "date"
                }
                ],
                order: [[6, 'desc']]
            });
        }

        /**
         * Captura evento para eliminar una transferencia
         * Realiza peticion HTTP
         */
        $('body').on('click', 'tbody .delete-transfer', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var token = $("input[name=_token]").val();
            var url = `${URL_RESOURCE}/${id}`;
            
            swal({
                title: '',
                text: "{{ __('dashboard.general.delete_resource') }}",
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No'
            }).then(function () {
                $.ajax({
                    url: url,
                    headers: {'X-CSRF-TOKEN': token},
                    type: 'DELETE',
                    datatype: 'json',
                    success: function (response) {
                        if (response.success) {
                            datatable_resource.DataTable().ajax.reload();
                            new Noty({
                                text: response.message,
                                type: 'success'
                            }).show();
                        } else if (response.message) {
                            new Noty({
                                text: response.message,
                                type: 'error'
                            }).show();
                        } else if (response.error) {
                            new Noty({
                                text: response.error,
                                type: 'error'
                            }).show();
                        } else {
                            new Noty({
                                text: "No se puede eliminar la solicitud de transferencia.",
                                type: 'error'
                            }).show();
                        }
                    },
                    error: function (e) {
                        if (e.responseJSON.errors) {
                            $.each(e.responseJSON.errors, function (index, element) {
                                if ($.isArray(element)) {
                                    new Noty({
                                        text: element[0],
                                        type: 'error'
                                    }).show();
                                }
                            });
                        } else if (e.responseJSON.message) {
                            new Noty({
                                text: e.responseJSON.message,
                                type: 'error'
                            }).show();
                        } else {
                            new Noty({
                                text: "No se puede eliminar la solicitud de transferencia.",
                                type: 'error'
                            }).show();
                        }
                    }
                });
            }).catch(swal.noop);
        });

        /**
         * Captura evento para aceptar transferencia
         * Realiza peticion HTTP
         */
        $('body').on('click', 'tbody .btn-accept-transfer', function (e) {
            e.preventDefault();
            var self = this;
            
            swal({
                title: '',
                text: '¿Seguro que quiere aceptar esta transferencia?',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No'
            }).then(function () {
                var id = $(self).data('id');
                    token = $("input[name=_token]").val(),
                    url = `${URL_RESOURCE}/${id}`;

                $.ajax({
                    url: url,
                    headers: {'X-CSRF-TOKEN': token},
                    type: 'PUT',
                    data: {},
                    datatype: 'json',
                    success: function (response) {
                        if (response.success) {
                            update_markers = true;
                            datatable_resource.DataTable().ajax.reload();
                            new Noty({
                                text: response.message,
                                type: 'success'
                            }).show();
                        } else if (response.message) {
                            new Noty({
                                text: response.message,
                                type: 'error'
                            }).show();
                        } else if (response.error) {
                            new Noty({
                                text: response.error,
                                type: 'error'
                            }).show();
                        } else {
                            new Noty({
                                text: error_message,
                                type: 'error'
                            }).show();
                        }
                    },
                    error: function (e) {
                        if (e.responseJSON.errors) {
                            $.each(e.responseJSON.errors, function (index, element) {
                                if ($.isArray(element)) {
                                    new Noty({
                                        text: element[0],
                                        type: 'error'
                                    }).show();
                                }
                            });
                        } else if (e.responseJSON.message) {
                            new Noty({
                                text: e.responseJSON.message,
                                type: 'error'
                            }).show();
                        } else {
                            new Noty({
                                text: error_message,
                                type: 'error'
                            }).show();
                        }
                    }
                });
            }).catch(swal.noop);
        });
    });
</script>