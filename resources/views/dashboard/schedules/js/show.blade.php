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
                    {data: 'customer.name'},
                    {data: 'customer.address'},
                    {data: 'comment'},
                    {data: 'responsable.name'},
                    {
                        render: function (data, type, row) {
                            return row.is_completed ? 'Si' : 'No'
                        }
                    },
                    {
                        render: function (data, type, row) {
                            var btn = '',
                                url = "{{ route('clientes.index') }}";

                            @if (Auth::user()->can('viewany', App\Models\Customer::class))
                                btn = `<a href="${url}/${row.customer_id}" class="btn btn-sm btn-primary btn-action-icon" title="Ver" data-toggle="tooltip"><i class="fas fa-eye"></i></a>`;
                            @endif

                            return btn;
                        }
                    }
                ],
                pageLength: 25,
                responsive: true
            });
        }
    });
</script>