<script>
    $(function () {
        const URL_RESOURCE = "{{ route('agendas.index') }}";
        const DATATABLE_RESOURCE = $("#datatable_schedules");

        initDataTable();

        function initDataTable() {
            DATATABLE_RESOURCE.DataTable({
                fixedHeader: true,
                processing: false,
                responsive: true,
                serverSide: true,
                ordering: false,
                ajax: URL_RESOURCE,
                pageLength: 25,
                columns: [
                    {data: 'id'},
                    {data: 'date'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        }


        $('body').on('click', 'tbody .delete-schedule', function (e) {
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
                                text: "No se puede eliminar la agenda en este momento.",
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
                                text: "No se puede eliminar la agenda en este momento.",
                                type: 'error'
                            }).show();
                        }
                    }
                });
            }).catch(swal.noop);
        });
    });
</script>