<script>
    $(function(){
        const FORM_RESOURCE = $("#form-customers");

        let inputs_files = $('.custom-file-input');
        let customer_map = $customer ? new CustomerMap('map-customer', $customer.latitude, $customer.longitude, $customer.address, true) : new CustomerMap('map-customer',null, null, null, true);
        customer_map.setMap();
        customer_map.setInitialMarker();

        $('select').select2();

        /**
         * Captura evento cuando seleccionan una imagen
         * Agrega preview de la imagen
         */
        inputs_files.on('change', function(event) {
            const id = this.id
            const img_target = document.getElementById('img-' + id);
            const [file] = this.files;

            if (img_target) {
                if (file) {
                    img_target.src = URL.createObjectURL(file)
                    img_target.classList.remove('d-none');
                } else {
                    img_target.src = '';
                    img_target.classList.add('d-none');
                }
            }
        });

        /**
         * Captura evento para eliminar la imagen del gasto.
         * Agrega input al DOM para cancelar eliminacion
         */
        $('.delete-img').on('click', function(e) {
            e.preventDefault();
            var parent = $(this).parent('.img-wrapper'); // closest
            var img = parent.find('img');
            var link_cancel_delete = parent.find('a.cancel-delete-img');
            var target = $(this).data('target');

            $(this).addClass('d-none');
            img.addClass('d-none');
            link_cancel_delete.removeClass('d-none');

            if (target) {
                // add input to delete server side
                var html = `<input class="delete" name="delete_${target}" type="hidden" value="1">`;
                parent.append(html);
            }
        });

        /**
         * Captura evento para cancelar eliminacion de imagen
         */
        $('.cancel-delete-img').on('click', function(e){
            e.preventDefault();
            var parent = $(this).parent('.img-wrapper');
            var img = parent.find('img');
            var link_delete = parent.find('.delete-img');
            var input_delete = parent.find('input.delete');

            $(this).addClass('d-none');
            img.removeClass('d-none');
            link_delete.removeClass('d-none');

            // remove input de delete
            input_delete.remove();
        });

        /**
         * Captura evento de cambio en switch de buscar direccion con coordenadas o direccion escrita
         */
        $('#location-switch').on('change', function(e) {
            if ($(this).is(':checked')) {
                $("#address").attr('readOnly', true);
                $("#latitude_search").attr('disabled', false);
                $("#longitude_search").attr('disabled', false);
            } else {
                $("#address").attr('readOnly', false);
                $("#latitude_search").attr('disabled', true);
                $("#longitude_search").attr('disabled', true);
            }
        });

        /**
         * Evento que captura keypress (escritura) en el campo de direccion en el formulario de cliente
         */
        $("#address").keypress( _.debounce( function(){
            if (customer_map.canGeocoding) {
                customer_map.geocoding();
            }
        }, 700));

        /**
         * Evento que captura keypress (escritura) en el campo de latitud en el formulario de cliente
         */
        $("#latitude_search").keypress( _.debounce( function() {
            if (customer_map.canGeocodingReverse && $('#latitude_search').val() && $('#longitude_search').val()) {
                customer_map.geocodingReverse();
            }
        }, 700));

        /**
         * Evento que captura keypress (escritura) en el campo de longitud en el formulario de cliente
         */
        $("#longitude_search").keypress( _.debounce( function() {
            if (customer_map.canGeocodingReverse && $('#latitude_search').val() && $('#longitude_search').val()) {
                customer_map.geocodingReverse();
            }
        }, 700));
        
        /**
         * Captura evento de submit de formulario de clientes
         */
        FORM_RESOURCE.on('submit', function (e) {
            e.preventDefault();
            httpSubmitForm();
        });

        /**
         * Peticion HTTP para enviar datos del cliente
         */
        function httpSubmitForm(confirm = false) {
            var url = FORM_RESOURCE.attr('action');
            var form = $('#form-customers')[0];
            var formData = new FormData(form);
            
            if (confirm) {
                url += '?confirm=1';
            }

            $.ajax({
                    url: url,
                    type: FORM_RESOURCE.attr('method'),
                    enctype: 'multipart/form-data',
                    data: formData,
                    processData: false,
                    contentType: false,
                success: function (response) {
                    if (response.success) {
                        if (is_creating_order) {
                            const customer = response.data.customer;
                            const customer_select = $('select#customer');
                            customer_select.attr('disabled', false);
                            customer_select.append(`<option value="${customer.id}" selected>${customer.name}</option>`);
                            customer_select.select2().val(customer.id).trigger('change');
                            $('#modal-new-customer').modal('hide');

                            new Noty({
                                text: 'Cliente creado',
                                type: 'success'
                            }).show();
                        } else {
                            window.location.href = response.data.redirect;
                        }
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

                                if (index == 'dni_contact_used' && Object.keys(e.responseJSON.errors).length  == 1) {
                                    swal({
                                        title: 'Desea confirmar el usuario?',
                                        text: element[0],
                                        type: 'question',
                                        showCancelButton: true,
                                        confirmButtonText: 'Si',
                                        cancelButtonText: 'No'
                                    }).then(function () {
                                        httpSubmitForm(true);
                                    }).catch(swal.noop);
                                } else {
                                    new Noty({
                                        text: element[0],
                                        type: 'error'
                                    }).show();
                                }
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
        }
    });
</script>