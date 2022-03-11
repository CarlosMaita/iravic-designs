<script>
    $(function () {
        const   URL_RESOURCE = "{{ route('pagos.index') }}",
                DATATABLE_RESOURCE = $("#datatable_payments");

        let btn_create_payment = $('#btn-create-payment'),
            modal_payments = $('#modal-payments'),
            form_payments = $('#form-payments');

        form_payments.find('select').select2();

        $('#payments-tab').on('click', function(e) {
            setTimeout(function(e) {
                DATATABLE_RESOURCE.DataTable()
                .columns.adjust()
                .responsive.recalc();
            }, 1000);
        });

        initDataTable();

        btn_create_payment.on('click', function(e) {
            e.preventDefault();
            form_payments.attr('action', URL_RESOURCE);
            form_payments.attr('method', 'POST');
            modal_payments.modal('show');
            modal_payments.find('.modal-title').text('Crear pago');
            $('#payment-visit').removeClass('d-none');
        });

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
         * 
         */
        // $('#amount').on('change', function() {
        // });
        $('#new_visit').change(function() {
            if (this.checked) {
                $('#payment-visit-fields').removeClass('d-none');
            } else {
                $('#payment-visit-fields').addClass('d-none');
            }
        });

        /**
        *
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

        function clearPaymentVisitForm() {
            form_payments.find('#visit-comment').val('');
            $("#new_visit").prop( "checked", false );
            $('#payment-visit-fields').addClass('d-none');
            form_payments.find('#visit-date').val('');
        }

        function clearModalForm() {
            form_payments.find('#amount').val('');
            form_payments.find('#comment').val('');
            form_payments.find('select').val('Seleccionar').trigger('change');
        }

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
                    {
                        render: function (data, type, row) {
                            if (typeof $customer !== 'undefined') {
                                return row.box ? row.box.id : '';
                            }

                            if (typeof $box !== 'undefined') {
                                return row.customer ? row.customer.name : '';
                            }

                            return '';
                        }
                    },
                    {data: 'date'},
                    {data: 'payment_method'},
                    {data: 'amount_str'},
                    {data: 'comment'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        }

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