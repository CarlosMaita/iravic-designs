<script>
    $(function () {
        const   URL_RESOURCE = "{{ route('visitas.index') }}",
                DATATABLE_RESOURCE = $("#datatable_visits");

        initDataTable();
        setDatePicker();

        /**
         * Captura evento de click en la pestana visitas
         * Espera 1 segundo para ajustar el tamano del datatable
         * Cuando el datatable no esta visible y es creado, no configura bien el width
         */
        $('#visits-tab').on('click', function(e) {
            setTimeout(function(e) {
                DATATABLE_RESOURCE.DataTable()
                .columns.adjust()
                .responsive.recalc();
            }, 1000);
        });

        /**
         * Inicializa el datatable de las visitas
         */
        function initDataTable() {
            var url_params = getDatatableUrlParams();

            DATATABLE_RESOURCE.DataTable({
                fixedHeader: true,
                processing: false,
                responsive: true,
                serverSide: true,
                ajax: URL_RESOURCE + url_params,
                pageLength: 25,
                columns: [
                    {data: 'date'},
                    {
                        render: function (data, type, row) {
                            if (typeof $customer !== 'undefined') {
                                return row.schedule ? row.schedule.id : '';
                            }

                            if (typeof $schedule !== 'undefined') {
                                return row.customer ? row.customer.name : '';
                            }

                            return '';
                        }
                    },
                    {
                        render: function (data, type, row) {
                            return row.suggested_collection_formatted ? row.suggested_collection_formatted : 'N/A';
                        }
                    },
                    {
                        render: function (data, type, row) {
                            return row.responsable ? row.responsable.name : '';
                        }
                    },
                    {
                        render: function (data, type, row) {
                            return row.is_completed ? 'Si' : 'No'
                        }
                    },
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        }

        /**
         *
         */
        function getDatatableUrlParams() {
            var params = '';

            if (typeof $customer !== 'undefined') {
                params += `?customer=${$customer.id}`;
            }

            if (typeof $schedule !== 'undefined') {
                params += `?schedule=${$schedule.id}`;
            }

            return params;
        }
        
        /**
         * Setea el datepicker para seleccionar la visita
         */
        function setDatePicker() {
            var inputs = $('.datepicker-form');

            inputs.each((index, element) => {
                var value = element.value;

                if (value) {
                    var dateParts = value.split("-");
                    var date = dateParts[2] + "/" +  dateParts[1] + "/" + dateParts[0];
                } else {
                    var date = new Date(value);
                }

                $(element).datepicker({
                    format: "dd-mm-yyyy",
                    todayBtn: "linked",
                    language: "es",
                    autoclose: true,
                    todayHighlight: true,
                    showOnFocus: true,
                }).datepicker("setDate", date)
                .end().on('keypress paste', function (e) {
                    // e.preventDefault();
                    // return false;
                });
            });
        }
    });
</script>