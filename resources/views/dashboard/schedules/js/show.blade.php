<script>
    $(function () {
        const URL_RESOURCE = "{{ route('agendas.show', [$schedule->id]) }}";
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
                                    html += `<button data-id="${row.id}" class="btn btn-sm btn-danger btn-action-icon" title="Ver" data-toggle="tooltip"><i class="fas fa-times"></i></button>`;
                                } else {
                                    html += `<button data-id="${row.id}" class="btn btn-sm btn-success btn-action-icon" title="Ver" data-toggle="tooltip"><i class="fas fa-check"></i></button>`;
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
    });
</script>