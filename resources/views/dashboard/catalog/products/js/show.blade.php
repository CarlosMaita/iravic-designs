<script>
    $(function() {
        const DATATABLE_IMAGES = $("#datatable_images");
        const URL_PRODUCT_IMAGES = "{{ route('producto-imagen.index') }}"

        /**
        *
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
                        var img = "<img class='img-fluid' src=\"" + row.url_img + "\" alt=\"\">";
                        return (img);
                    }
                },
            ]
        });
    });
</script>