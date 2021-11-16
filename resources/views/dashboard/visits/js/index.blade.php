<script>
    $(function () {
        const   URL_RESOURCE = "{{ route('visitas.index') }}",
                DATATABLE_RESOURCE = $("#datatable_visits");

        let btn_create_visit = $('#btn-create-visit'),
            modal_resource = $('#modal-visits'),
            form_resource = $('#form-visits');

        initDataTable();
        setDatePicker();

        $('#visits-tab').on('click', function(e) {
            setTimeout(function(e) {
                DATATABLE_RESOURCE.DataTable()
                .columns.adjust()
                .responsive.recalc();
            }, 1000);
        });

        btn_create_visit.on('click', function(e) {
            e.preventDefault();
            form_resource.attr('action', URL_RESOURCE);
            form_resource.attr('method', 'POST');
            modal_resource.modal('show');
            modal_resource.find('.modal-title').text('Crear Visita');
        });

        form_resource.on('submit', function(e) {
            e.preventDefault();

            var url = form_resource.attr('action');
            var form = $('#form-visits')[0];
            var formData = new FormData(form);
            
            $.ajax({
                    url: url,
                    type: form_resource.attr('method'),
                    enctype: 'multipart/form-data',
                    data: formData,
                    processData: false,
                    contentType: false,
                success: function (response) {
                    if (response.success) {
                        new Noty({
                            text: response.message,
                            type: 'success'
                        }).show();
                        
                        modal_resource.modal('hide');
                        DATATABLE_RESOURCE.DataTable().ajax.reload();
                    } else if (response.error) {
                        new Noty({
                            text: response.error,
                            type: 'error'
                        }).show();
                    } else {
                        new Noty({
                            text: "{{ __('dashboard.general.operation_error') }}",
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
                    }else if (e.responseJSON.message){
                        new Noty({
                            text: e.responseJSON.message,
                            type: 'error'
                        }).show();
                    } else if (e.responseJSON.error){
                        new Noty({
                            text: e.responseJSON.error,
                            type: 'error'
                        }).show();
                    } else {
                        new Noty({
                            text: "{{ __('dashboard.general.operation_error') }}",
                            type: 'error'
                        }).show();
                    }
                }
            });
        });

        /**
        *
        */
        modal_resource.on('hidden.coreui.modal', function(e) {
            $('#input-method-put').remove();
            form_resource.attr('action', '');
            form_resource.attr('method', '');
            form_resource.find('#visit-date').val('');
            form_resource.find('#visit-comment').val('');
            form_resource.find('.modal-title').text('');
        });

        $('body').on('click', 'tbody .delete-visit', function (e) {
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
                                text: "No se puede eliminar la visita en este momento.",
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
                                text: "No se puede eliminar la visita en este momento.",
                                type: 'error'
                            }).show();
                        }
                    }
                });
            }).catch(swal.noop);
        });


        $('body').on('click', 'tbody .edit-visit', function (e) {
            var id = $(this).data('id'),
                url = `${URL_RESOURCE}/${id}`;

            $.ajax({
                    url: `${url}/edit`,
                    type: 'GET',
                    contentType: 'application/json',
                success: function (response) {
                    form_resource.append(`<input id="input-method-put" type="hidden" name="_method"" value="PUT">`);
                    form_resource.attr('action', url);
                    form_resource.attr('method', 'POST');
                    form_resource.find('#visit-date').val(response.date);
                    form_resource.find('#visit-comment').val(response.comment);
                    modal_resource.modal('show');
                    modal_resource.find('.modal-title').text('Editar Visita');
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
                    }else if (e.responseJSON.message){
                        new Noty({
                            text: e.responseJSON.message,
                            type: 'error'
                        }).show();
                    } else if (e.responseJSON.error){
                        new Noty({
                            text: e.responseJSON.error,
                            type: 'error'
                        }).show();
                    } else {
                        new Noty({
                            text: "{{ __('dashboard.general.operation_error') }}",
                            type: 'error'
                        }).show();
                    }
                }
            });
        });

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

        function setDatePicker() {
            // $('.datepicker-form').datepicker({
            //     uiLibrary: 'bootstrap4'
            // });

            // return;
            // .gj-picker-bootstrap
            // max-width: 300px;

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