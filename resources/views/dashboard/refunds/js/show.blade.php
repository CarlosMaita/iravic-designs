<script>
    $(function() {
        let datatable_refund_products = $('#datatable_refund_products').DataTable({
            ordering: false,
            pageLength: 25
        });

        let datatable_order_products = $('#datatable_order_products').DataTable({
            ordering: false,
            pageLength: 25
        });

        $('#products-tab').on('click', function(e) {
            setTimeout(function(e) {
                datatable_refund_products
                .columns.adjust()
                .responsive.recalc();
            }, 1000);
        });

        $('#products-order-tab').on('click', function(e) {
            setTimeout(function(e) {
                datatable_order_products
                .columns.adjust()
                .responsive.recalc();
            }, 1000);
        });
    });
</script>