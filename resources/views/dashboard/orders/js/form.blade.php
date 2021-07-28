<script>
    $(function(){
        const FORM_RESOURCE_ORDERS = $("#form-orders");
        const btn_add_customer = $('#add-customer');
        const btn_cancel_new_customer = $('#btn-cancel-new-customer');
        const container_new_customer = $('#container-new-customer');
        const modal_new_customer = $('#modal-new-customer');
        const select_customer = $('select#customer');

        $('select').select2();

        FORM_RESOURCE_ORDERS.on('submit', function (e) {
            e.preventDefault();
            var form = $('#form-orders')[0];
            var formData = new FormData(form);
            
            $.ajax({
                    url: FORM_RESOURCE_ORDERS.attr('action'),
                    type: FORM_RESOURCE_ORDERS.attr('method'),
                    enctype: 'multipart/form-data',
                    data: formData,
                    processData: false,
                    contentType: false,
                success: function (response) {
                    if (response.success) {
                        window.location.href = response.data.redirect;
                    } else if (response.message){
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

        /**
        *
        */
        btn_add_customer.on('click', function(e) {
            e.preventDefault();
            modal_new_customer.modal('show');
            select_customer.attr('disabled', true);
        });
        /**
        *
        */
        btn_cancel_new_customer.on('click', function(e) {
            e.preventDefault();
            modal_new_customer.modal('hide');
            select_customer.attr('disabled', false);
        });

        /**
        *
        */
        modal_new_customer.on('hidden.coreui.modal', function (e) {
            select_customer.attr('disabled', false);
        });
    });
</script>