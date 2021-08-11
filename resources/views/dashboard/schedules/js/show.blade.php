<script>
    $(function () {
        const URL_RESOURCE = "{{ route('agendas.show', [$schedule->id]) }}";
        const URL_VISITS = "{{ route('visitas.index') }}";
        const DATATABLE_RESOURCE = $("#datatable_schedule_visits");

        initDataTable();

        function initDataTable() {
            DATATABLE_RESOURCE.DataTable({
                ajax: {
                    url: URL_RESOURCE,
                    dataSrc: function(data) {
                        return data.visits ? data.visits : [];
                    },
                },
                columns: [
                    {
                        render: function (data, type, row) {
                            var html = '<div class="d-flex align-items-start">',
                                url = "{{ route('clientes.index') }}";

                            @if (Auth::user()->can('viewany', App\Models\Customer::class))
                                html += `<a href="${url}/${row.customer_id}" class="btn btn-sm btn-primary btn-action-icon" title="Ver" data-toggle="tooltip"><i class="fas fa-eye"></i></a>`;
                            @endif

                            html += `<span>${row.customer.name}</span></div>`

                            return html;
                        }
                    },
                    {data: 'customer.address'},
                    {data: 'customer.zone.name'},
                    {data: 'comment'},
                    {
                        render: function (data, type, row) {
                            var html = row.responsable ? `<span>${row.responsable.name}</span>` : '<span>Por asignar</span>';

                            @if (Auth::user()->can('updateResponsable', App\Models\Visit::class))
                                html += `<button data-id="${row.id}"  class="btn btn-sm btn-success btn-action-icon edit-visit" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></button>`;
                            @endif

                            return html;
                        }
                    },
                    {
                        render: function (data, type, row) {
                            var html = row.is_completed ? '<span>Si</span>' : '<span>No</span>';

                            @if (Auth::user()->can('complete', App\Models\Visit::class))
                                if (row.is_completed) {
                                    html += `<button data-id="${row.id}" data-to-complete="0" class="btn btn-sm btn-danger btn-action-icon btn-complete-visit" title="Ver" data-toggle="tooltip"><i class="fas fa-times"></i></button>`;
                                } else {
                                    html += `<button data-id="${row.id}" data-to-complete="1" class="btn btn-sm btn-success btn-action-icon btn-complete-visit" title="Ver" data-toggle="tooltip"><i class="fas fa-check"></i></button>`;
                                }
                            @endif

                            return html;
                        }
                    }
                ],
                pageLength: 25,
                responsive: true
            });
        }


        
        $('body').on('click', 'tbody .btn-complete-visit', function (e) {
            e.preventDefault();
            var id = $(this).data('id'),
                complete = $(this).data('to-complete'),
                message = complete ? 'Seguro que quiere marcar la visita como completada?' : 'Seguro que quiere marcar la visita como no completada?',
                token = $("input[name=_token]").val(),
                url = `${URL_VISITS}/${id}/complete`;

            console.log(complete);
            console.log(message);

            swal({
                title: '',
                text: message,
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No'
            }).then(function () {
                $.ajax({
                    url: url,
                    headers: {'X-CSRF-TOKEN': token},
                    type: 'PUT',
                    data: `is_completed=${complete}`,
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
                                text: "No se puede actualizar el estado de la visita en este momento.",
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
                                text: "No se puede actualizar el estado de la visita en este momento.",
                                type: 'error'
                            }).show();
                        }
                    }
                });
            }).catch(swal.noop);
        });
    });
</script>