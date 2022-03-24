<script>
    $(function() {
        const FORM_RESOURCE = $("#form-products");
        const DATATABLE_IMAGES = $("#datatable_images");
        const URL_PRODUCT_IMAGES = "{{ route('producto-imagen.index') }}"

        /**
         * Inicializa dropzone de imagenes en el formulario de producto
         * Cuando hay imagenes el dropzone, se procesa la cola en el evento submit del formulario
         * y Aca se ejecuta el condigo dentro del evento 'sendingmultiple'
         * Mandando la informacion del formulario junto a las imagenes
         */
        let myDropzone = new Dropzone("#myDropzone", {
            url: URL_RESOURCE,
            acceptedFiles: 'image/*',
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 10,
            maxFiles: 10,
            maxFilesize: 2,
            acceptedFiles: 'image/*',
            addRemoveLinks: true,
            init: function() {
                dzClosure = this; // Makes sure that 'this' is understood inside the functions below.

                //send all the form data along with the files:
                this.on("sendingmultiple", function(data, xhr, formData) {
                    $(":input[name]", $("#form-products")).each(function () {
                        formData.append(this.name, $(':input[name=' + this.name + ']', $("form")).val());   
                    });
                    
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == XMLHttpRequest.DONE) {
                            var response = JSON.parse(xhr.responseText);

                            if (response.success) {
                                window.location.href = response.data.redirect;
                            } else {
                                dzClosure.removeAllFiles(true);

                                if (response.errors) {
                                    $.each(response.errors, function (index, element) {
                                        if ($.isArray(element)) {
                                            new Noty({
                                                text: element[0],
                                                type: 'error'
                                            }).show();
                                        }
                                    });
                                } else if (response.error){
                                    new Noty({
                                        text: response.error,
                                        type: 'error'
                                    }).show();
                                } else if (response.message){
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
                            }
                        }
                    }
                });
            }
        });

        /**
         * Inicializa datatable de imagenes de un producto
         */
        DATATABLE_IMAGES.DataTable({
            fixedHeader: true,
            processing: false,
            responsive: true,
            serverSide: true,
            ajax: `${URL_PRODUCT_IMAGES}?producto={{ $product->id }}`,
            pageLength: 25,
            columns: [
                {
                    render: function (data, type, row) {
                        var img = "<img src=\"" + row.url_img + "\" style=\"max-width:150px;\"alt=\"\">";
                        return (img);
                    }
                },
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
        
        /**
         * Captura evento submit de formulario de producto
         * Si no hay imagenes el dropzone entra en el else
         * Si hay imagenes, manda a procesar la cola (Se ejecuta el codigo de la inicializacion del dropzone)
         */
        FORM_RESOURCE.on('submit', function (e) {
            e.preventDefault();

            if (myDropzone.files.length > 0) { 
                myDropzone.processQueue();
            } else {
                var form = $('#form-products')[0];
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
                    }
                });
            }
        });

        /**
         * Captura evento para eliminar una imagen
         * Realiza peticion HTTP
         */
        $('body').on('click', 'tbody .delete-image', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let token = $("input[name=_token]").val();
            let url = `${URL_PRODUCT_IMAGES}/${id}`;
            
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
                            DATATABLE_IMAGES.DataTable().ajax.reload();
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
                                text: "No se puede eliminar la imagen en este momento.",
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
                                text: "No se puede eliminar la imagen en este momento.",
                                type: 'error'
                            }).show();
                        }
                    }
                });
            }).catch(swal.noop);
        });
    });
</script>