<script>
    $(function () {
        const URL_RESOURCE = "{{ route('gastos.index') }}";
        
        let datatable_resource = $("#datatable_spendings"),
            btn_create_spending = $('#btn-create-spending'),
            modal_spendings = $('#modal-spendings'),
            form_spendings = $('#form-spendings'),
            is_editing = false;

        initDataTable();

        $('#spendings-tab').on('click', function(e) {
            setTimeout(function(e) {
                datatable_resource.DataTable()
                .columns.adjust()
                .responsive.recalc();
            }, 1000);
        });

        $('.custom-file-input').on('change', function(event) {
            var id = this.id,
                img_target = document.getElementById('img-' + id),
                img_wrapper = document.getElementById('img-' + id + '-wrapper'),
                [file] = this.files;
                
            console.log('Entra');
            console.log(img_target);

            if (img_target) {
                console.log('Entra en img target');

                if (file) {
                    console.log(1);
                    img_target.src = URL.createObjectURL(file)
                    img_target.classList.remove('d-none');
                    $(img_wrapper).find('.delete-img').removeClass('d-none');
                } else {
                    console.log(2);
                    img_target.src = '';
                    img_target.classList.add('d-none');
                    $(img_wrapper).find('.delete-img').addClass('d-none');
                }
            }
        });

        $('.delete-img').on('click', function(e) {
            e.preventDefault();
            var parent = $(this).parent('.img-wrapper'), // closest
                img = parent.find('img'),
                link_cancel_delete = parent.find('a.cancel-delete-img'),
                target = $(this).data('target'),
                input = $(`#${target}`);

            $(this).addClass('d-none');
            img.addClass('d-none');
            // parent.addClass('d-none');
            input.val('');

            if (is_editing) {
                link_cancel_delete.removeClass('d-none');

                if (target) {
                    // add input to delete server side
                    var html = `<input class="delete" name="delete_${target}" type="hidden" value="1">`;
                    parent.append(html);
                }
            }
        });

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

        btn_create_spending.on('click', function(e) {
            e.preventDefault();
            form_spendings.attr('action', URL_RESOURCE);
            form_spendings.attr('method', 'POST');
            modal_spendings.modal('show');
            modal_spendings.find('.modal-title').text('Crear pago');
        });

        form_spendings.on('submit', function(e) {
            e.preventDefault();
            var url = form_spendings.attr('action');
            var form = $('#form-spendings')[0];
            var formData = new FormData(form);
            
            $.ajax({
                    url: url,
                    type: form_spendings.attr('method'),
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
                        
                        modal_spendings.modal('hide');
                        datatable_resource.DataTable().ajax.reload();
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

        $('body').on('click', 'tbody .delete-spending', function (e) {
            e.preventDefault();
            var id = $(this).data('id'),
                token = $("input[name=_token]").val(),
                url = `${URL_RESOURCE}/${id}`;
            
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
                            datatable_resource.DataTable().ajax.reload();

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

        $('body').on('click', 'tbody .edit-spending', function (e) {
            var id = $(this).data('id'),
                url = `${URL_RESOURCE}/${id}`;

            $.ajax({
                    url: `${url}/edit`,
                    type: 'GET',
                    contentType: 'application/json',
                success: function (response) {
                    form_spendings.append(`<input id="input-method-put" type="hidden" name="_method"" value="PUT">`);
                    form_spendings.attr('action', url);
                    form_spendings.attr('method', 'POST');
                    form_spendings.find('#amount').val(response.amount);
                    form_spendings.find('#comment').val(response.comment);
                    modal_spendings.modal('show');
                    modal_spendings.find('.modal-title').text('Editar gasto');

                    if (response.picture) {
                        is_editing = true;
                        form_spendings.find('#img-picture-wrapper .delete-img').removeClass('d-none');
                        form_spendings.find('#img-picture').removeClass('d-none');
                        form_spendings.find('#img-picture').attr('src', response.url_picture);
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

        /**
        *
        */
        modal_spendings.on('hidden.coreui.modal', function(e) {
            $('#input-method-put').remove();
            is_editing = false;
            form_spendings.attr('action', '');
            form_spendings.attr('method', '');
            form_spendings.find('#amount').val('');
            form_spendings.find('#comment').val('');
            form_spendings.find('#picture').val('');
            form_spendings.find('#img-picture-wrapper .delete-img').addClass('d-none');
            form_spendings.find('#img-picture-wrapper .cancel-delete-img').addClass('d-none');
            form_spendings.find('#img-picture').addClass('d-none');
            form_spendings.find('#img-picture').attr('src', '');
            modal_spendings.find('.modal-title').text('');
        });

        function initDataTable() {
            var url_params = getUrlParams();

            datatable_resource.DataTable({
                fixedHeader: true,
                processing: false,
                responsive: true,
                serverSide: true,
                ajax: URL_RESOURCE + url_params,
                pageLength: 25,
                columns: [
                    {
                        render: function (data, type, row) {
                            var img = "<a href='" + row.url_picture + "' target='_blank'><img src=\"" + row.url_picture + "\" style=\"width:50px;border-radius:25px;\"alt=\"\"></a>";
                            return (img);
                        }
                    },
                    {data: 'date'},
                    {data: 'amount_str'},
                    {data: 'comment'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        }

        function getUrlParams() {
            var params = '';

            if (typeof $box !== 'undefined') {
                params += `?box=${$box.id}`;
            }

            return params;
        }
    });
</script>