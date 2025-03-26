<script>
    $(function () {
        const   URL_RESOURCE = "{{ route('visitas.index') }}",
                DATATABLE_RESOURCE = $("#datatable_visits");

        let suggested_collection_negative_alert = `{{ __('dashboard.visits.planning_collection_negative_alert') }}`,
            suggested_collection_positive_alert = `{{ __('dashboard.visits.planning_collection_positive_alert') }}`;

        let btn_create_visit = $('#btn-create-visit'),
            modal_resource = $('#modal-visits'),
            form_resource = $('#form-visits');

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
         * Captura evento click en el boton para crear una visita y abre el modal
         */
        btn_create_visit.on('click', function(e) {
            e.preventDefault();
            form_resource.attr('action', URL_RESOURCE);
            form_resource.attr('method', 'POST');
            modal_resource.modal('show');
            modal_resource.find('.modal-title').text('Crear Visita');
        });

        /**
         * Captura evento submit del formulario de visitas
         */
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
                        setPlanningCollectionAlert( response.planning_collection);
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
         * Ajusta alerta de planificacion de cobro
         */
        function setPlanningCollectionAlert ( planningCollection){
            if (planningCollection.check) {
                $('#planning-collection-alert').addClass('d-none');
            } else {
                $('#planning-collection-alert').removeClass('d-none');
                if (planningCollection.rest > 0) {
                    $('#planning-collection-alert').find('#message-alert').text(suggested_collection_positive_alert.replace(':customer', planningCollection.customer_name).replace(':suggested_collection_total', planningCollection.rest_formatted));
                } else {
                    $('#planning-collection-alert').find('#message-alert').text(suggested_collection_negative_alert.replace(':customer', planningCollection.customer_name).replace(':suggested_collection_total', planningCollection.rest_formatted));
                }
            }
        }

        /**
         * Limpia formulario de visita cuando cierra el modal
         */
        modal_resource.on('hidden.coreui.modal', function(e) {
            $('#input-method-put').remove();
            form_resource.attr('action', '');
            form_resource.attr('method', '');
            form_resource.find('#visit-date').val('');
            form_resource.find('#visit-comment').val('');
            form_resource.find('#is-collection').prop('checked', false);
            form_resource.find('.modal-title').text('');
            //suggested collection
            form_resource.find('#div-suggested-collection').addClass('d-none');
            form_resource.find('#suggested-collection').val('');
            form_resource.find('#is-collection-checkbox').prop('checked', false);
            form_resource.find('#is-collection-hidden').val(0);
        });

        /**
         * Captura evento para eliminar visita. Realiza peticion HTTP para eliminarla en BD
         */
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
                            setPlanningCollectionAlert( response.planning_collection);
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

        /**
         * Captura evento para editar una visita. Realiza peticion HTTP para obtener datos
         */
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
                    form_resource.find('#is-collection').val(response.is_collection);
                    if (response.is_collection) {
                        form_resource.find('#div-suggested-collection').removeClass('d-none');
                        form_resource.find('#suggested-collection').val(response.suggested_collection);
                        form_resource.find('#is-collection-checkbox').prop('checked', true);
                        form_resource.find('#is-collection-hidden').val(1);
                    }else{
                        form_resource.find('#div-suggested-collection').addClass('d-none');
                        form_resource.find('#suggested-collection').val(response.suggested_collection);
                        form_resource.find('#is-collection-checkbox').prop('checked', false);
                        form_resource.find('#is-collection-hidden').val(0);
                    }
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

        /**
         * Captura evento para mostrar el campo de monto sugerido
         */
         form_resource.find('#is-collection-checkbox').on('change', function(e) {
            if ($(this).is(':checked')) {
                form_resource.find('#is-collection-hidden').val(1);
                form_resource.find('#div-suggested-collection').removeClass('d-none');
            } else {
                form_resource.find('#is-collection-hidden').val(0);
                form_resource.find('#div-suggested-collection').addClass('d-none');
            }
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