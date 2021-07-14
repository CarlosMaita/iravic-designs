@push('css')
<link rel="stylesheet" href="{{asset("plugins/datatables/extensions/Responsive/css/dataTables.responsive.css")}}">
@endpush

@push('js')
<script src="{{asset("js/pages/uiTables.js")}}"></script>
<script src="{{asset("plugins/datatables/extensions/Responsive/js/dataTables.responsive.js")}}"></script>
<script>
    $(function () {
        $.fn.dataTable.ext.errMode = 'none';
        UiTables.init();

        var config_datatable = {
            'responsive': true,
            "language": {
                infoEmpty: 'Sin registros disponibles',
            },
        }

        $.extend($.fn.dataTable.defaults, config_datatable);
    });
</script>
@endpush
