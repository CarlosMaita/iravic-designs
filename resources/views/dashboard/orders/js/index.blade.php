<script>
    $(function () {
        const URL_RESOURCE = "{{ route('ventas.index') }}";
        const DATATABLE_RESOURCE = $("#datatable_orders");

      
        DATATABLE_RESOURCE.DataTable({
            ajax: URL_RESOURCE,
            paging : true,
            columns: [
                {data: 'id' },
                {data: 'customer.name'},
                {data: 'payment_method', searchable: false},
                {data: 'total', searchable: false},
                {data: 'date' },
                {data: 'action', orderable: false, searchable: false}
            ],
            deferRender: true,
            order: [[4, 'desc']],
            pageLength: 25,
            processing: false,
            responsive: true,
            serverSide: true,
            searchDelay : 1000,
        });
    });
</script>
