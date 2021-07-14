<script>
    $(function () {
        const URL_PERMISSIONS = "{{ route('permisos.index') }}";
        const DATATABLE_PERMISSIONS = $("#datatable_permissions");

        initDataTable();

        function initDataTable() {
            DATATABLE_PERMISSIONS.DataTable({
                fixedHeader: true,
                processing: false,
                responsive: true,
                serverSide: true,
                ajax: URL_PERMISSIONS,
                pageLength: 25,
                columns: [
                    {data: 'display_name'},
                    {data: 'name'},
                    {data: 'description'}
                ]
            });
        }
    });
</script>