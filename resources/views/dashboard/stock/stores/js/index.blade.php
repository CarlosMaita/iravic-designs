<script>
    $(function () {
        const DATATABLE_RESOURCE = $("#datatable_stores");
        const URL_RESOURCE = "{{ route('depositos.index') }}";

        // Initialize select2 only if available
        if (typeof $.fn.select2 !== 'undefined') {
            $('select').select2({
                allowClear: true,
                placeholder: "Seleccionar"
            });
        }

        initDataTable();  

        /**
         * Captura evento de ver stocks de un stock
         * Realiza peticion HTTP para obtener stocks
         * Luego se manda actualizar los stocks del producto
         */
        $('body').on('click', 'tbody .btn-show-stock', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var url = `${URL_RESOURCE}/${id}?stocks=1`;
            
            $.ajax({
                url: url,
                type: 'GET',
                datatype: 'json',
                contentType: 'application/json',
                success: function (response) {
                    if (response) {
                        updateModalStocksContent(response);
                    } else {
                        new Noty({
                            text: "No se puede obtener información del producto en este momento.",
                            type: 'error'
                        }).show();
                    }
                },
                error: function (e) {
                    if (e.responseJSON.message) {
                        new Noty({
                            text: e.responseJSON.message,
                            type: 'error'
                        }).show();
                    } else {
                        new Noty({
                            text: "No se puede obtener información del producto en este momento.",
                            type: 'error'
                        }).show();
                    }
                }
            });
        });

        /**
         * Captura evento de eliminar store
         * Realiza peticion HTTP
         */
        $('body').on('click', 'tbody .delete-store', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let token = $("input[name=_token]").val();
            let url = `${URL_RESOURCE}/${id}`;
            
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
                            DATATABLE_RESOURCE.DataTable().ajax.reload();
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
                                text: "No se puede eliminar el store en este momento.",
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
                                text: "No se puede eliminar el store en este momento.",
                                type: 'error'
                            }).show();
                        }
                    }
                });
            }).catch(swal.noop);
        });


        /**
         * Inicializa datatable de productos
         */
        function initDataTable() {
            DATATABLE_RESOURCE.DataTable({
                fixedHeader: true,
                processing: false,
                responsive: true,
                serverSide: true,
                ajax: {
                    url: URL_RESOURCE,
                    data: function(d) {
                        d.name      = $("#name").val() ? $("#name").val() : null;
                        d.type      = $("#type").val() ? $("#type").val() : null;
                    },
                },
                pageLength: 10,
                columns: [
                    {data: 'name'},
                    {data: 'type.name', orderable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        }

        
    });
</script>