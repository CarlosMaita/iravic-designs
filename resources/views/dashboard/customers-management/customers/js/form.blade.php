<script>
    $(function(){
        const FORM_RESOURCE = $("#form-customers");

        var customer_map = $customer ? new CustomerMap('map-customer', $customer.latitude, $customer.longitude) : new ClienteMap('map-customer');
        customer_map.setMap();
        customer_map.setInitialMarker();

        $('select').select2();

        $("#address").keypress( _.debounce( function(){
            if (customer_map.canGeocoding) {
                customer_map.geocoding();
            }
        }, 700));

        FORM_RESOURCE.on('submit', function (e) {
            e.preventDefault();
            httpSubmitForm();
        });


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

                                if (index == 'dni_contact_used' && Object.keys(e.responseJSON.errors).length  == 1) {
                                    swal({
                                        title: 'Desea confirmar el usuario?',
                                        text: element[0],
                                        type: 'question',
                                        showCancelButton: true,
                                        confirmButtonText: 'Si',
                                        cancelButtonText: 'No'
                                    }).then(function () {
                                        console.log('Debe confirmarse el formulario');
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
        }
    });
</script>