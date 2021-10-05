<script>
    $(function () {
        const URL_RESOURCE = "{{ route('zonas.index') }}";
        const URL_SORT_ZONES = "{{ route('zonas.sort') }}";
        let sortableJs = new SortableJs('zonas-container', '.btn-move-zone');

        sortableJs.create(sortZones);

        $('body').on('click', '.delete-zone', function (e) {
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
                            location.reload();
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
                                text: "No se puede eliminar la zona en este momento.",
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
                                text: "No se puede eliminar la zona en este momento.",
                                type: 'error'
                            }).show();
                        }
                    }
                });
            }).catch(swal.noop);
        });

        function sortZones() {
            var zones = [];

            $('.zone-item').each((index, item) => {
                zones.push(item.dataset.id);
            });

            httpSortZones(zones);
        }

        function httpSortZones(zones) {
            var token = $("input[name=_token]").val();

            $.ajax({
                url: URL_SORT_ZONES,
                headers: {'X-CSRF-TOKEN': token},
                type: 'POST',
                data: {
                    zones: zones
                },
                dataType:'json',
                enctype: 'multipart/form-data',
                success: function (response) {
                    if (response.success) {
                        location.reload();
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
                            text: "No se ha podido actualizar las posiciones de las zonas en este momento.",
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
                            text: "No se ha podido actualizar las posiciones de las zonas en este momento.",
                            type: 'error'
                        }).show();
                    }
                }
            });
        }
    });
</script>