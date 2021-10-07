<script>
    $(function () {
        const URL_RESOURCE = "{{ route('agendas.show', [$schedule->id]) }}";
        const URL_VISITS = "{{ route('visitas.index') }}";
        const DATATABLE_RESOURCE = $("#datatable_schedule_visits");

        let btn_open_map = $('#btn-open-map'),
            form_visits = $('#form-visits'),
            modal_visits = $('#modal-visits'),
            modal_map = $('#modal-map'),
            visit_editing_id = null,
            role_select = $('#role-map'),
            zone_select = $('#zones-map'),
            update_markers = false,
            schedule_map = new ScheduleMap('map-schedule', $schedule, URL_RESOURCE, zone_select, role_select);

        schedule_map.setMap();

        $('.datatable-zone').DataTable();

        $('.btn-collapse-zone').on('click', function(e) {
            $(this).parents('.zone-customers').collapse('hide');
        });

        $('.btn-sort-schedule').on('click', function(e) {
            route.calcZonesRoute($zones);
        });

        $('#visit-responsable').select2({
            dropdownParent: modal_visits
        });
        
        zone_select.select2({
            allowClear: true,
            placeholder: "Seleccionar",
            dropdownParent: modal_map
        });

        role_select.select2({
            allowClear: true,
            placeholder: "Seleccionar",
            dropdownParent: modal_map
        });

        /**
        *
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
        *
        */
        modal_visits.on('hidden.coreui.modal', function(e) {
            form_visits.find('#visit-responsable').val('Seleccionar').trigger('change');
            $visit_editing_id = null;
        });

        /**
        *
        */
        form_visits.on('submit', function(e) {
            e.preventDefault();
            var url = `${URL_VISITS}/${$visit_editing_id}/update-responsable`;
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

        /*
        *
        */
        role_select.on('change', function(e) {
            schedule_map.removeMarkers();
            schedule_map.httpGetVisits();
        });

        /*
        *
        */
        zone_select.on('change', function(e) {
            schedule_map.removeMarkers();
            schedule_map.httpGetVisits();
        });

        /**
        * Complete visit
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
        * Http get visit to edit responsable
        */
        $('body').on('click', 'tbody .btn-edit-visit', function (e) {
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
                            form_visits.find('#visit-responsable').val(response.user_responsable_id).trigger('change');
                        }
                        
                        modal_visits.modal('show');
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
    });
</script>