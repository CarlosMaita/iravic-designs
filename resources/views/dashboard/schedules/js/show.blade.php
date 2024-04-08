<script>
    $(function () {
        const URL_SCHEDULES = "{{ route('agendas.index') }}"
        const URL_RESOURCE_VISITS = "{{ route('visitas.index') }}"
        const URL_RESOURCE = "{{ route('agendas.show', [$schedule->id]) }}";
        const URL_VISITS = "{{ route('visitas.index') }}";
        const DATATABLE_RESOURCE = $("#datatable_schedule_visits");

        let btn_open_map = $('#btn-open-map'),
            form_responsable = $('#form-responsable'),
            form_visits = $('#form-visits')
            modal_visits = $('#modal-visits'),
            modal_responsable = $('#modal-responsable'),
            modal_map = $('#modal-map'),
            visit_editing_id = null,
            role_select = $('#role-map'),
            zone_select = $('#zones-map'),
            update_markers = false,
            schedule_map = new ScheduleMap('map-schedule', $schedule, URL_RESOURCE, zone_select, role_select);

        schedule_map.setMap();
        setDatePicker();

        $('.datatable-zone').DataTable();

        /**
         * Captura evento para cerrar collape de una zona (Listado de clientes)
         */
        $('.btn-collapse-zone').on('click', function(e) {
            $(this).parents('.zone-customers').collapse('hide');
        });

        /**
         * Captura evento para iniciar proceso de ordenar clientes de una zona dentro de la agenda
         */
        $('.btn-sort-schedule').on('click', function(e) {
            route.calcZonesRoute($zones);
        });

        /**
         * Inicializa el selector de responsable como Select2
         */
        $('#visit-responsable').select2({
            dropdownParent: modal_responsable
        });
        
        /**
         * Inicializa el selector de zona como Select2
         * Se utiliza para filtrar clientes en el mapa
         */
        zone_select.select2({
            allowClear: true,
            placeholder: "Seleccionar",
            dropdownParent: modal_map
        });

        /**
         * Inicializa el selector de rol como Select2
         * Se utiliza para filtrar clientes en el mapa
         */
        role_select.select2({
            allowClear: true,
            placeholder: "Seleccionar",
            dropdownParent: modal_map
        });

        /**
         * Captura evento de click para abrir mapa de la agenda
         * Manda a mostrar todos los clientes/visitas de la agenda. Si el mapa no ha sido mostrado
         * Si el mapa ha sido mostrado, solamente muestra los marcadores
         */
        btn_open_map.on('click', function(e) {
            e.preventDefault();
            modal_map.modal('show');

            if (!schedule_map.showed && !update_markers) {
                schedule_map.showed = true;
                schedule_map.showMarkers($visits);
            } else if (update_markers) {
                update_markers = false;
                schedule_map.removeMarkers();
                schedule_map.httpGetVisits();
            }
        });

        /**
         * Captura evento de cierre modal responsable. Reinicia el selector de responsable
         */
        modal_responsable.on('hidden.coreui.modal', function(e) {
            form_responsable.find('#visit-responsable').val('Seleccionar').trigger('change');
            $visit_editing_id = null;
        });

        /**
         * Captura evento de submit del formulario de una visita
         */
        form_responsable.on('submit', function(e) {
            e.preventDefault();
            var url = `${URL_VISITS}/${$visit_editing_id}/update-responsable`;
            var form = $('#form-responsable')[0];
            var formData = new FormData(form);
            
            $.ajax({
                url: url,
                type: form_responsable.attr('method'),
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

                        modal_responsable.modal('hide');
                        $(`#visit-${response.visita.id}-responsable`).text(response.visita.responsable.name);
                        // DATATABLE_RESOURCE.DataTable().ajax.reload();
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

        /*
         * Captura evento de cambio de tipo clientes.
         * Llama a realizar peticion HTTP para obtener listado de visitas filtradas
         */
        role_select.on('change', function(e) {
            schedule_map.removeMarkers();
            schedule_map.httpGetVisits();
        });

        /*
         * Captura evento de zona.
         * Llama a realizar peticion HTTP para obtener listado de visitas filtradas
         */
        zone_select.on('change', function(e) {
            schedule_map.removeMarkers();
            schedule_map.httpGetVisits();
        });

        /**
         * Captura evento para Completar visita
         * Realiza peticion HTTP para actualizar en la BD
         */
        $('body').on('click', 'tbody .btn-complete-visit', function (e) {
            e.preventDefault();
            var id = $(this).data('id'),
                complete = $(this).data('to-complete'),
                token = $("input[name=_token]").val(),
                error_message = "No se puede actualizar el estado de la visita en este momento.",
                message = complete ? 'Seguro que quiere marcar la visita como completada?' : 'Seguro que quiere marcar la visita como no completada?',
                url = `${URL_VISITS}/${id}/complete`,
                data = `is_completed=${complete}`;

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
                    data: data,
                    datatype: 'json',
                    success: function (response) {
                        if (response.success) {
                            update_markers = true;
                            location.reload();
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
                                text: error_message,
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
                                text: error_message,
                                type: 'error'
                            }).show();
                        }
                    }
                });
            }).catch(swal.noop);
        });

        /**
        * Captura evento de editar responsable de una visita.
         * Realiza peticion HTTP para obtener detalles del responsable de la visita
         */
        $('body').on('click', 'tbody .btn-edit-responsable', function (e) {
            e.preventDefault();
            var id = $(this).data('id'),
                url = `${URL_VISITS}/${id}`;

            $.ajax({
                url: `${url}/edit`,
                type: 'GET',
                contentType: 'application/json',
                success: function (response) {
                    if (response) {
                        $visit_editing_id = response.id;

                        if (response.user_responsable_id) {
                            form_responsable.find('#visit-responsable').val(response.user_responsable_id).trigger('change');
                        }
                        
                        modal_responsable.modal('show');
                    } else {
                        new Noty({
                                    text: 'No podemos obtener los detalles de la visita en este momento.',
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
         * Captura evento para editar una visita
         * Realiza peticion HTTP para obtener detalles de la visita y llenar el formulario de visitas
         */
        $('body').on('click', 'tbody .edit-visit', function (e) {
            var id = $(this).data('id'),
                url = `${URL_RESOURCE_VISITS}/${id}`;

            $.ajax({
                    url: `${url}/edit`,
                    type: 'GET',
                    contentType: 'application/json',
                success: function (response) {
                    form_visits.append(`<input id="form-visitas-customer-id" type="hidden" name="customer_id"" value="${response.customer_id}">`);
                    form_visits.append(`<input id="form-visitas-input-method-put" type="hidden" name="_method"" value="PUT">`);
                    form_visits.attr('action', url);
                    form_visits.attr('method', 'POST');
                    form_visits.find('#visit-date').val(response.date);
                    form_visits.find('#visit-comment').val(response.comment);
                    modal_visits.modal('show');
                    modal_visits.find('.modal-title').text('Editar Visita');
                    setDatePicker();
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
        * Captura evento de postergar la visita.
        */
        $('body').on('click', 'tbody .btn-pending-to-visit', function (e) {
            e.preventDefault();
            var id = $(this).data('id'),
                url = `${URL_VISITS}/${id}/postpone`,
                token = $("input[name=_token]").val(),
                message = 'Seguro que quiere postergar la visita para otro día y marcar al cliente como pendiente por agendar?';

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
                    // data: data,
                    datatype: 'json',
                    success: function (response) {
                        if (response.success) {
                            update_markers = true;
                            window.location = `${URL_SCHEDULES}`;
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
                                text: error_message,
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
                                text: error_message,
                                type: 'error'
                            }).show();
                        }
                    }
                });

            }).catch(swal.noop);

        });


        /**
         * Captura evento de cerrar modal de formulario visitas
         * Limpia el formulario
         */
        modal_visits.on('hidden.coreui.modal', function(e) {
            $('#form-visitas-customer-id').remove();
            $('#form-visitas-input-method-put').remove();
            form_visits.attr('action', '');
            form_visits.attr('method', '');
            form_visits.find('#visit-date').val('');
            form_visits.find('#visit-comment').val('');
            form_visits.find('.modal-title').text('');
        });

        /**
         * Captura evento de submit de formulario visitas
         */
        form_visits.on('submit', function(e) {
            e.preventDefault();

            var url = form_visits.attr('action');
            var form = $('#form-visits')[0];
            var formData = new FormData(form);
            
            $.ajax({
                    url: url,
                    type: form_visits.attr('method'),
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
                        
                        modal_visits.modal('hide');

                        params = '';
                        if (response.prev_schedule) {
                            params = `?open-zone=${response.visita.customer.zone_id}`;
                        }
                        
                        window.location.href = `${URL_SCHEDULES}/${response.visita.schedule_id}${params}`;
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
         * Setea el datepicker para crear/editar nueva visita
         */
        function setDatePicker() {
            var inputs = $('.datepicker-form');

            inputs.each((index, element) => {
                var value = element.value;

                if (value) {
                    var dateParts = value.split("-");
                    var date = dateParts[0] + "/" +  dateParts[1] + "/" + dateParts[2];
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