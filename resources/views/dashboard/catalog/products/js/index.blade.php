<script>
    $(function () {
        const URL_RESOURCE = "{{ route('productos.index') }}";
        const DATATABLE_RESOURCE = $("#datatable_products");

        var form_advanced_search = $('#form-advanced-search'),
            advanced_search = $('#adv-search'),
            btn_advanced_search = $('#btn-advanced-search'),
            btn_clear_filter = form_advanced_search.find('.clear-form'),
            btn_close_filter = form_advanced_search.find('#close-advance-search'),
            select_brand = $('#brand'),
            select_category = $('#category'),
            select_gender = $('#gender'),
            select_color = $('#color'),
            select_size = $('#size');

        $('select').select2();

        initDataTable();

        function initDataTable() {
            DATATABLE_RESOURCE.DataTable({
                fixedHeader: true,
                processing: false,
                responsive: true,
                serverSide: true,
                ajax: {
                    url: URL_RESOURCE,
                    data: function(d) {
                        d.brand     = $("#brand").val() > 0 ? $("#brand").val() : null;
                        d.category  = $("#category").val() > 0 ? $("#category").val() : null;
                        d.gender    = $("#gender").val() ? $("#gender").val() : null;
                        d.color     = $("#color").val() > 0 ? $("#color").val() : null;
                        d.size      = $("#size").val() > 0 ? $("#size").val() : null;
                    },
                },
                pageLength: 25,
                columns: [
                    {data: 'name'},
                    {data: 'code'},
                    {data: 'gender'},
                    {data: 'brand.name'},
                    {data: 'category.name'},
                    {
                        render: function (data, type, row) {
                            return row.is_regular ? 'No' : 'Si';
                        }
                    },
                    {data: 'regular_price_str'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        }


        $('body').on('click', 'tbody .delete-resource', function (e) {
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
                                text: "No se puede eliminar el producto en este momento.",
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
                                text: "No se puede eliminar la categoría en este momento.",
                                type: 'error'
                            }).show();
                        }
                    }
                });
            }).catch(swal.noop);
        });

        /**
         * 
         */
        form_advanced_search.on('submit', function(e) {
            e.preventDefault();
            DATATABLE_RESOURCE.DataTable().ajax.reload();
        });

        /**
         * 
         */
        btn_advanced_search.click(function() {
            if (advanced_search.hasClass('show')) {
                advanced_search.collapse('hide');
            } else {
                advanced_search.collapse('show');
            }
        });

        /**
         * 
         */
        btn_clear_filter.click(function() {
            select_brand.val('Todas').trigger('change');
            select_category.val('Todas').trigger('change');
            select_gender.val('Todos').trigger('change');
            select_color.val('Todos').trigger('change');
            select_size.val('Todas').trigger('change');
        });

        /**
         * 
         */
        btn_close_filter.click(function() {
            advanced_search.collapse('hide');
        });
    });
</script>