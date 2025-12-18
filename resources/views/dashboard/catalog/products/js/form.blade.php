<script>
    $(function() {
        const FORM_RESOURCE = $("#form-products");
        const URL_PRODUCT_IMAGES = "{{ route('producto-imagen.index') }}"

        const handlerBeforeUnload = function (event) {
            event.preventDefault();
            event.returnValue = "Any changes will be lost";
            return "Any changes will be lost";
        }
        window.addEventListener('beforeunload', handlerBeforeUnload);


        /**
         * Captura evento submit de formulario de producto
         * Para productos regulares, solo se envía el formulario
         * Para productos no regulares, las imágenes ya se subieron de forma asíncrona con vue-dropzone
         */
        FORM_RESOURCE.on('submit', function (e) {
            e.preventDefault();

            // remove the handler
            window.removeEventListener('beforeunload', handlerBeforeUnload);

            var form = $('#form-products')[0];
            var formData = new FormData(form);
            
            // Enviando formulario
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
                    } else if (response.message) {
                        new Noty({
                            text: response.message,
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
                    // add the handler again
                    window.addEventListener('beforeunload', handlerBeforeUnload);
                }
            });
        });

        /** Metodos **/

        /**
         * Determines if the product is regular.
         *
         * @return {boolean} Returns true if the product is regular, false otherwise.
         */
        const isProductRegular = () => {
            if ($('input[name="is_regular"]:checked').val() == 1) {
                return true
            }
            return false
        }
    
    });
</script>