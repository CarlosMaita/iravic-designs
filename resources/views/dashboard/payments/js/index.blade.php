<script>
    $(function () {
        const   URL_RESOURCE = "{{ route('pagos.index') }}",
                DATATABLE_RESOURCE = $("#datatable_payments");

        let btn_create_payment = $('#btn-create-payment'),
            modal_payments    = $('#modal-payments'),
            modal_payments_installments = $('#modal-payments-installments'),
            customer_selector = $("#customer"),
            form_payments     = $('#form-payments'),
            form_payment_installments = $('#form-payment-installments');

        //Select of payments 
        form_payments.find('select').select2({
            dropdownParent: $("#modal-payments")
        });
        
        form_payment_installments.find('select').select2({
            dropdownParent: $("#modal-payments-installments")
        });

        initDataTable();

        /**
         * Captura evento de click en la pestana pagos
         * Espera 1 segundo para ajustar el tamano del datatable
         * Cuando el datatable no esta visible y es creado, no configura bien el width
         */
        $('#payments-tab').on('click', function(e) {
            setTimeout(function(e) {
                DATATABLE_RESOURCE.DataTable()
                .columns.adjust()
                .responsive.recalc();
            }, 1000);
        });

        /**
         *  Captura evento para abrir modal y crear un pago
         */
        btn_create_payment.on('click', function(e) {
            e.preventDefault();
            form_payments.attr('action', URL_RESOURCE);
            form_payments.attr('method', 'POST');
            modal_payments.modal('show');
            modal_payments.find('.modal-title').text('Crear pago');
            $('#payment-visit').removeClass('d-none');
        });


         /**
         * captura evento para mostrar modal de pago de cuota
         * 
         * 
         * */
         $('body').on('click', 'tbody .btn-payment-installments', function (e) {
            e.preventDefault();
            let customer = $(this).data('customer'),
                motive = $(this).data('motive'),
                customer_id = $(this).data('customer_id'),
                visit_date_now = $(this).data('visit_date_now'),
                suggested_collection_amount = $(this).data('suggested_collection_amount');
            
            form_payment_installments.find('#motive').val(motive);
            form_payment_installments.find('#customer_id').val(customer_id);
            form_payment_installments.find('#visit_date_now').val(visit_date_now);
            form_payment_installments.find('#suggested_collection_amount').val(suggested_collection_amount);
            form_payment_installments.attr('action', URL_RESOURCE);
            form_payment_installments.attr('method', 'POST');
            modal_payments_installments.modal('show');
            modal_payments_installments.find('.modal-title').text('Pagos de cuotas');
            modal_payments_installments.find('#customer').val(customer);
        })

        /**
         * captura evento para mostrar modal de pago de visita
         * 
         *
         * */
         form_payment_installments.on('submit', function(e) {
            e.preventDefault();

            var url = form_payment_installments.attr('action');
            var form = $('#form-payment-installments')[0];
            var formData = new FormData(form);
            
            $.ajax({
                    url: url,
                    type: form_payment_installments.attr('method'),
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
                        
                        $customer = response.customer;
                        clearModalForm();
                        modal_payments_installments.modal('hide');
                        location.reload();
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
                    } else if (e.responseJSON.message){
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



        // customer_selector.select2({
        //     dropdownParent: $("#modal-payments")
        // });

        /**
         * Captura evento submit para crear un pago
         * Realiza peticion HTTP
         */
        form_payments.on('submit', function(e) {
            e.preventDefault();

            var url = form_payments.attr('action');
            var form = $('#form-payments')[0];
            var formData = new FormData(form);
            
            $.ajax({
                    url: url,
                    type: form_payments.attr('method'),
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
                        
                        $customer = response.customer;
                        clearModalForm();
                        clearPaymentVisitForm();
                        modal_payments.modal('hide');
                        DATATABLE_RESOURCE.DataTable().ajax.reload();
                        updateDatatableOperations();
                        updateBalanceLabel($customer.balance);
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
                    } else if (e.responseJSON.message){
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
         * Captura evento para eliminar un pago
         * Realiza peticion HTTP
         */
        $('body').on('click', 'tbody .delete-payment', function (e) {
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
                            $customer = response.customer;
                            DATATABLE_RESOURCE.DataTable().ajax.reload();
                            updateDatatableOperations();
                            updateBalanceLabel($customer.balance);

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
                                text: "No se puede eliminar el pago en este momento.",
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
                                text: "No se puede eliminar el pago en este momento.",
                                type: 'error'
                            }).show();
                        }
                    }
                });
            }).catch(swal.noop);
        });

        /**
         * Captura evento para iniciar proceso para editar un pago
         * Realiza una peticion HTTP para obtener datos del pago y rellenar el formulario
         */
        $('body').on('click', 'tbody .edit-payment', function (e) {
            $('#payment-visit').addClass('d-none');
            clearPaymentVisitForm();

            var id = $(this).data('id'),
                url = `${URL_RESOURCE}/${id}`;

            $.ajax({
                    url: `${url}/edit`,
                    type: 'GET',
                    contentType: 'application/json',
                success: function (response) {
                    form_payments.append(`<input id="input-method-put" type="hidden" name="_method"" value="PUT">`);
                    form_payments.attr('action', url);
                    form_payments.attr('method', 'POST');
                    form_payments.find('#amount').val(response.amount);
                    form_payments.find('#payment_method').val(response.payment_selected);
                    form_payments.find('#comment').val(response.comment);
                    modal_payments.modal('show');
                    modal_payments.find('.modal-title').text('Editar pago');
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
         * Captura evento de cambio en check para activar nueva visita
         */
        $('#new_visit').change(function() {
            if (this.checked) {
                $('#payment-visit-fields').removeClass('d-none');
            } else {
                $('#payment-visit-fields').addClass('d-none');
            }
            // Hack para correguir error con la libreria select2
            form_payments.find('select').select2("open");
            form_payments.find('select').select2("close");
        });

        /**
         * Captura evento de cerrar modal de pagos
         * Vacia los campos del formulario
         */
        modal_payments.on('hidden.coreui.modal', function(e) {
            $('#input-method-put').remove();
            form_payments.attr('action', '');
            form_payments.attr('method', '');
            form_payments.find('#amount').val('');
            form_payments.find('#payment_method').val('');
            form_payments.find('#comment').val('');
            modal_payments.find('.modal-title').text('');
        });

        /**
         * Vacia formulario de visita en modal de pagos
         */
        function clearPaymentVisitForm() {
            form_payments.find('#visit-comment').val('');
            $("#new_visit").prop( "checked", false );
            $('#payment-visit-fields').addClass('d-none');
            form_payments.find('#visit-date').val('');
        }

        /**
         * Vacia formulario de pago
         */
        function clearModalForm() {
            form_payments.find('#amount').val('');
            form_payments.find('#comment').val('');
            form_payments.find('select').val('Seleccionar').trigger('change');
        }

        /**
         * Inicializa datatable de pagos
         */
        function initDataTable() {
            var url_params = getUrlPaymentParams();

            DATATABLE_RESOURCE.DataTable({
                fixedHeader: true,
                processing: false,
                responsive: true,
                serverSide: true,
                ajax: URL_RESOURCE + url_params,
                pageLength: 25,
                ordering: false,
                columns: [
                    {data: 'id'},
                    {data: 'box.id'},
                    {data: 'customer.name'},
                    // {
                    //     render: function (data, type, row) {
                    //         if (typeof $customer !== 'undefined') {
                    //             return row.box ? row.box.id : '';
                    //         }

                    //         if (typeof $box !== 'undefined') {
                    //             return row.customer ? row.customer.name : '';
                    //         }

                    //         return '';
                    //     }
                    // },
                    {data: 'date'},
                    {data: 'payment_method'},
                    {data: 'amount_str'},
                    {data: 'comment'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        }

        /**
         * Retorna string para ser usado como query parametro con variables de cliente seleccionado o caja de un cliente
         */
        function getUrlPaymentParams() {
            var params = '';

            if (typeof $customer !== 'undefined') {
                params += `?customer=${$customer.id}`;
            }

            if (typeof $box !== 'undefined') {
                params += `?box=${$box.id}`;
            }

            return params;
        }
    });
</script>