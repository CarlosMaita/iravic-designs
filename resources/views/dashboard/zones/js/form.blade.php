<script>
    $(function(){
        const FORM_RESOURCE = $("#form-zones");

        let zone_map = $zone 
                        ? new ZoneMap('map-zone', $zone.destination_latitude, $zone.destination_longitude, $zone.destination_address, true) 
                        : new ZoneMap('map-zone');
        zone_map.setMap();
        zone_map.setInitialMarker();


        /**
         * Evento que captura keypress (escritura) en el campo de direccion en el formulario de zonas
         */
        $("#address").keypress(_.debounce( function(){
            if (zone_map.canGeocoding) {
                zone_map.geocoding();
            }
        }, 700));

        /**
         * Evento que captura submit del formulario de zona y envia datos a la API
         */
        FORM_RESOURCE.on('submit', function (e) {
            e.preventDefault();
            var form = $('#form-zones')[0];
            var formData = new FormData(form);
            
            $.ajax({
                    url: FORM_RESOURCE.attr('action'),
                    type: FORM_RESOURCE.attr('method'),
                    enctype: 'multipart/form-data',
                    data: formData,
                    processData: false,
                    contentType: false,
                success: function (response) {
                    if (response.success) {
                        window.location.href = response.data.redirect;
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
                    } else if (e.responseJSON.error){
                        new Noty({
                            text: e.responseJSON.error,
                            type: 'error'
                        }).show();
                    } else if (e.responseJSON.message){
                        new Noty({
                            text: e.responseJSON.message,
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