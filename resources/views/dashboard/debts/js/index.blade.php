<script>
    $(function () {
        const   URL_RESOURCE = "{{ route('deudas.index') }}",
                DATATABLE_RESOURCE = $("#datatable_debts");

        let btn_create = $('#btn-create-debt'),
            modal = $('#modal-debts'),
            form = $('#form-debts');

            form.find('select').select2();

        $('#payments-tab').on('click', function(e) {
            setTimeout(function(e) {
                DATATABLE_RESOURCE.DataTable()
                .columns.adjust()
                .responsive.recalc();
            }, 1000);
        });

        initDataTable();

        btn_create.on('click', function(e) {
            e.preventDefault();
            form.attr('action', URL_RESOURCE);
            form.attr('method', 'POST');
            modal.modal('show');
            modal.find('.modal-title').text('Crear Deuda');
        });

        form.on('submit', function(e) {
            e.preventDefault();

            var url = form.attr('action');
            var formData = new FormData($('#form-debts')[0]);
            
            $.ajax({
                    url: url,
                    type: form.attr('method'),
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
                        
                        clearModalForm();
                        modal.modal('hide');
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

        $('body').on('click', 'tbody .delete-debt', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var token = $("input[name=_token]").val();
            var url = `${URL_RESOURCE}/${id}`;
            
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
                                text: "No se puede eliminar la deuda en este momento.",
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
                                text: "No se puede eliminar la deuda en este momento.",
                                type: 'error'
                            }).show();
                        }
                    }
                });
            }).catch(swal.noop);
        });

        $('body').on('click', 'tbody .edit-debt', function (e) {
            var id = $(this).data('id'),
                url = `${URL_RESOURCE}/${id}`;

            $.ajax({
                    url: `${url}/edit`,
                    type: 'GET',
                    contentType: 'application/json',
                success: function (response) {
                    form.append(`<input id="input-method-put" type="hidden" name="_method"" value="PUT">`);
                    form.attr('action', url);
                    form.attr('method', 'POST');
                    form.find('#amount').val(response.amount);
                    form.find('#comment').val(response.comment);
                    modal.modal('show');
                    modal.find('.modal-title').text('Editar Deuda');
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
        *
        */
        modal.on('hidden.coreui.modal', function(e) {
            $('#input-method-put').remove();
            form.attr('action', '');
            form.attr('method', '');
            form.find('#amount').val('');
            form.find('#comment').val('');
            modal.find('.modal-title').text('');
        });

        function clearModalForm() {
            form.find('amount').val('');
            form.find('comment').val('');
            form.find('select').val('Seleccionar').trigger('change');
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
                columns: [
                    {data: 'id'},
                    {data: 'date'},
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