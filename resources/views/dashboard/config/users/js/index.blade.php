<script>
    $(function () {
        const URL_USERS = "{{ route('usuarios.index') }}";
        const DATATABLE_USERS = $("#datatable_users");

        initDataTable();

        /**
         * Inicializa la datatable de usuarios
         */
        function initDataTable() {
            DATATABLE_USERS.DataTable({
                fixedHeader: true,
                processing: false,
                responsive: true,
                serverSide: true,
                ajax: URL_USERS,
                pageLength: 25,
                columns: [
                    {data: 'name'},
                    {data: 'email'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        }

        /**
         * Captura evento para eliminar un usuario
         * Realiza peticion HTTP
         */
        $('body').on('click', 'tbody .delete-user', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let token = $("input[name=_token]").val();
            let url = `${URL_USERS}/${id}`;
            
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
                            DATATABLE_USERS.DataTable().ajax.reload();
                            new Noty({
                                text: response.message,
                                type: 'success'
                            }).show();

                            if (response.cerrar_sesion == true) {
                                window.location.href = "{{url('/login')}}";
                            }
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
                                text: "No se puede eliminar el usuario en este momento.",
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
                                text: "No se puede eliminar el usuario en este momento.",
                                type: 'error'
                            }).show();
                        }
                    }
                });
            }).catch(swal.noop);
        });
    });
</script>